<?php 
namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class DocumentController extends Controller
{
// INDEX + PREVIEW + LOGGING + KATEGORI FOLDER
    public function index(Request $request)
    {
        $query = Document::query();


// FITUR #4: FILTER STATUS
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }


// FITUR #4: FILTER TYPE
        if ($request->has('type') && $request->type !== '') {
            $query->where('type', $request->type);
        }


// FITUR #3: SEARCHING
        if ($request->has('q') && $request->q !== '') {
            $query->where('title', 'like', "%" . $request->q . "%");
        }


        $documents = $query->latest()->paginate(10);


        return view('admin.documents.index', compact('documents'));
    }


// CREATE
    public function create()
    {
        return view('admin.documents.create');
    }


// STORE
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:umum,sk,sop,pmk,formulir',
            'doc_path' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png',
        ]);


// FITUR #1: FILE UPLOAD
        $file = $request->file('doc_path');
        $filename = time() . '_' . $file->getClientOriginalName();
        Storage::disk('public')->putFileAs('uploads/documents', $file, $filename);


// FITUR #5: VALIDASI KHUSUS (contoh: ukuran max 5MB)
        if ($request->file('doc_path')->getSize() > 15 * 1024 * 1024) {
            return back()->with('error', 'Ukuran file maksimal 5MB.');
        }


        Document::create([
            'title' => $request->title,
            'type' => $request->type,
            'doc_path' => $filename,
            'upload_by' => Auth::guard('admin')->id(),
            'status' => $request->status ?? true,
        ]);


        return redirect()->route('admin.document.index')->with('success', 'Dokumen berhasil ditambahkan');
    }


    public function edit(Document $document)
    {
        return view('admin.documents.edit', compact('document'));
    }


// UPDATE
    public function update(Request $request, Document $document)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:umum,sk,sop,pmk,formulir',
            'doc_path' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png',
        ]);


        $path = $document->doc_path;
       // Jika upload file baru
        if ($request->hasFile('doc_patch')) {

            // hapus file lama
            Storage::disk('public')->delete('uploads/documents/' . $document->doc_path);

            $file = $request->file('doc_patch');
            $filename = time() . '_' . $file->getClientOriginalName();

            Storage::disk('public')->putFileAs('uploads/documents', $file, $filename);

            $document->doc_path = $filename;

            $document->title = $request->title;
            $document->type = $request->type;
            $document->status = $request->status ?? true;
            $document->save();
        
        return redirect()->route('admin.document.index')->with('success', 'Dokumen berhasil diperbarui');
    }
}


// DELETE
public function destroy(Document $document)
{
    // Hapus file fisik jika ada
    if ($document->doc_path && Storage::disk('public')->exists('uploads/documents/' . $document->doc_path)) {
        Storage::disk('public')->delete('uploads/documents/' . $document->doc_path);
    }

    // Hapus data database
    $document->delete();

    return redirect()->back()->with('success', 'Dokumen berhasil dihapus');
}


// FITUR #2: DOWNLOAD FILE
    public function download(Document $document)
    {
        $doc = Document::findOrFail($document);

        $path = storage_path('app/' . $doc->doc_path);

    // Bersihkan nama file agar aman
        $safeName = preg_replace('/[\/\\\\]/', '-', $doc->title);

        $extension = pathinfo($doc->doc_path, PATHINFO_EXTENSION);
        $finalName = $safeName . '.' . $extension;

        return response()->download($path, $finalName);
    }
// ================================
// PREVIEW ROUTE DI CONTROLLER
    public function preview(Document $document)
    {
        $path = storage_path('app/' . $document->doc_path);


        if (!file_exists($path)) {
            abort(404);
        }


// preview hanya untuk PDF atau gambar
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));


        if (in_array($ext, ['pdf'])) {
            return response()->file($path);
        }


        if (in_array($ext, ['jpg','jpeg','png'])) {
            return response()->file($path);
        }


        return back()->with('error', 'Preview hanya tersedia untuk PDF dan gambar');
    }

    public function show(){

    }
}