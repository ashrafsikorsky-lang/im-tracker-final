<?php

namespace App\Http\Controllers;

use App\Models\DocumentationEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DocumentationController extends Controller
{
    public function index()
    {
        $docs = DocumentationEntry::orderBy('class_name')->get();
        
        $docs->transform(function ($doc) {
            $doc->documentation_html = Str::markdown($doc->documentation);
            
            $doc->documentation_html = preg_replace_callback(
                '/<pre><code(?: class="language-(.*?)")?>(.*?)<\/code><\/pre>/s',
                function ($matches) {
                    $language = $matches[1] ?? 'plaintext';
                    $code = htmlspecialchars_decode($matches[2]); 
                    return '<div class="code-block language-'.e($language).'"><pre><code>'.e($code).'</code></pre></div>';
                },
                $doc->documentation_html
            );
            return $doc;
        });

        return view('docs.index', ['docs' => $docs]);
    }
}