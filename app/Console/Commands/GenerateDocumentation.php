<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Models\DocumentationEntry;
use OpenAI\Laravel\Facades\OpenAI; 
use ReflectionClass;
use ReflectionMethod;
use Illuminate\Support\Str; 

class GenerateDocumentation extends Command
{
    protected $signature = 'generate:docs';
    protected $description = 'Generate system documentation using OpenAI and save to database';

    public function handle()
    {
        $this->info('Starting documentation generation...');
        $controllerFiles = File::allFiles(app_path('Http/Controllers'));

        foreach ($controllerFiles as $file) { 
            $path = $file->getRealPath();
            $classContent = File::get($path);
            
            $namespace = $this->getNamespace($classContent);
            $className = $this->getClassName($classContent);

            if (empty($namespace) || empty($className)) continue;

            $fqcn = $namespace . '\\' . $className; 

            if (!class_exists($fqcn, false)) require_once $path;
            if (!class_exists($fqcn)) continue;

            $this->line("Processing: {$fqcn}");

            try {
                $reflection = new ReflectionClass($fqcn);
                $methods = collect($reflection->getMethods(ReflectionMethod::IS_PUBLIC))
                    ->filter(fn($m) => $m->getDeclaringClass()->getName() === $fqcn)
                    ->map(fn($m) => $m->getName())
                    ->values()->all();

                $views = $this->detectViewFiles($classContent);
                $models = $this->detectModelFiles($classContent);
                $tests = $this->detectTestFiles($className);
                $factories = $this->detectFactoryFiles($models);

                $summary = [
                    'class_name' => $fqcn,
                    'file_path' => Str::replaceFirst(base_path() . DIRECTORY_SEPARATOR, '', $path),
                    'methods' => $methods,
                    'associated_views' => $views,
                    'associated_models' => $models,
                    'associated_test_files' => $tests,
                    'associated_factory_files' => $factories,
                ];
                
                $jsonSummary = json_encode($summary, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

                $prompt = "Anda adalah seorang penulis teknikal pakar Laravel. Tugas anda adalah untuk menjana dokumentasi teknikal yang jelas dan profesional dalam format Markdown untuk kelas Controller Laravel yang diberikan berdasarkan nama kelasnya, laluan fail, metod-metod, serta fail-fail view, model, ujian, dan factory yang berkaitan.\n\nSila ikut struktur ini:\n1.  **Nama Kelas (Class Name)**\n2.  **Laluan Fail (File Path)**\n3.  **Penerangan (Description)**\n4.  **Gambaran Keseluruhan Metod (Methods Overview)**\n5.  **Model Digunakan (Used Models)**\n6.  **View Digunakan (Used Views)**\n7.  **Fail Ujian Berkaitan (Associated Test Files)**\n8.  **Fail Factory (Factory Files)**\n\nPastikan penjelasan jelas dan membantu untuk pembangun junior atau ahli pasukan baharu. Formatkan output dengan kemas dalam Markdown.\n\nBerikut adalah ringkasan JSON mengenai kelas tersebut:\n\n```json\n" . $jsonSummary . "\n```";

                $this->line("Sending request to OpenAI for {$fqcn}...");
                
                $openaiResponse = OpenAI::chat()->create([
                    'model' => 'gpt-3.5-turbo', // Using 3.5 to save your API credits!
                    'messages' => [
                        ['role' => 'system', 'content' => 'Anda adalah penjana dokumentasi perisian yang sangat mahir.'],
                        ['role' => 'user', 'content' => $prompt]
                    ],
                ]);

                $documentationContent = $openaiResponse->choices[0]->message->content;

                DocumentationEntry::updateOrCreate(
                    ['class_name' => $fqcn],
                    [
                        'source_path' => $path,
                        'metadata' => $summary, 
                        'documentation' => $documentationContent,
                    ]
                );

                $this->info("Dokumentasi dijana dan disimpan untuk: {$fqcn}");

            } catch (\Exception $e) {
                $this->error("Gagal memproses {$fqcn}: " . $e->getMessage());
            }
        }
        $this->info('Documentation generation complete!');
        return 0; 
    }

    protected function getNamespace($content) {
        if (preg_match('/namespace\s+([^;]+);/m', $content, $matches)) return $matches[1];
        return null;
    }

    protected function getClassName($content) {
        if (preg_match('/class\s+(\w+)/m', $content, $matches)) return $matches[1];
        return null;
    }

    protected function detectViewFiles($content) {
        preg_match_all('/(?:view|View::make)\s*\(\s*[\'"]([a-zA-Z0-9_.-]+)[\'"]/', $content, $matches);
        return array_unique($matches[1] ?? []);
    }

    protected function detectModelFiles($content) {
        preg_match_all('/(?:use\s+App\\\Models\\\([A-Za-z0-9_]+);|new\s+([A-Z][A-Za-z0-9_]+)\s*\(|([A-Z][A-Za-z0-9_]+)::)/m', $content, $matches);
        $models = array_filter(array_merge($matches[1], $matches[2], $matches[3]));
        return collect($models)->unique()->filter(function ($modelName) {
            return File::exists(app_path("Models/{$modelName}.php"));
        })->values()->all();
    }
    
    protected function detectTestFiles($className) {
        $foundTests = [];
        if (File::exists(base_path("tests/Feature/{$className}Test.php"))) $foundTests[] = "tests/Feature/{$className}Test.php";
        return $foundTests;
    }

    protected function detectFactoryFiles($models) {
        return collect($models)->map(fn($model) => "database/factories/{$model}Factory.php")->filter(fn($path) => File::exists(base_path($path)))->values()->all();
    }
}