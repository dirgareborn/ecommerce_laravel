<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $groupedDocuments = Document::where('status', 1)
        ->orderBy('type')
        ->orderBy('title')
        ->get()
        ->groupBy('type');


        return view('front.pages.document', compact('groupedDocuments'));
    }
    public function preview($filename)
    {

        $path = Storage::disk('public')->path('uploads/' . $filename);

     if (!file_exists($path)) {
        return abort(404, "File tidak ditemukan: $filename");
    }

    $mime = mime_content_type($path);

    return response()->file($path, [
        'Content-Type' => $mime,
    ]);

}
public function download($id)
{
    $doc = Document::findOrFail($id);

    $filePath = public_path('uploads/documents/' . $doc->file_path);

    if (!file_exists($filePath)) {
        abort(404, "File tidak ditemukan");
    }

    return response()->download($filePath, $doc->title . '.' . pathinfo($doc->file_path, PATHINFO_EXTENSION));
}
}
