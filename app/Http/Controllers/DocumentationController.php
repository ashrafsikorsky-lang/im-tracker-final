<?php
namespace App\Http\Controllers;

use App\Models\DocumentationEntry;
use Illuminate\Support\Str;

class DocumentationController extends Controller
{
    public function index()
    {
        $docs = DocumentationEntry::orderBy('class_name')->get();
        
        $docs->transform(function ($doc) {
            $doc->documentation_html = Str::markdown($doc->documentation);
            return $doc;
        });

        return view('docs_index', ['docs' => $docs]);
    }
}