<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class FileController extends Controller
{
    protected $baseFolder = 'public/uploads';

    // Tampilkan daftar folder dan file
    public function index(Request $request)
    {
        $currentFolder = $request->query('folder', '');
        $path = $this->baseFolder.($currentFolder ? '/'.$currentFolder : '');
        $files = Storage::files($path);
        $folders = Storage::directories($path);

        return view('admin.files.index', compact('files','folders','currentFolder'));
    }

    // Upload file
    public function upload(Request $request)
    {
        $folder = $request->input('folder', '');
        $path = $this->baseFolder.($folder ? '/'.$folder : '');
        $request->validate([
            'files.*' => 'required|file|max:10240',
        ]);

        $uploadedFiles = [];
        if($request->hasFile('files')){
            foreach($request->file('files') as $file){
                $uploadedFiles[] = $file->store($path);
            }
        }

        return response()->json(['success'=>true, 'files'=>$uploadedFiles]);
    }

    // Buat folder baru
    public function createFolder(Request $request)
    {
        $request->validate([
            'folder_name' => 'required|string',
            'parent_folder' => 'nullable|string',
        ]);

        $path = $this->baseFolder.($request->parent_folder ? '/'.$request->parent_folder : '').'/'.$request->folder_name;

        if(!Storage::exists($path)){
            Storage::makeDirectory($path);
        }

        return back()->with('success','Folder berhasil dibuat.');
    }

    // Download file
    public function download($folder='',$filename)
    {
        $path = $this->baseFolder.($folder ? '/'.$folder : '').'/'.$filename;
        if(!Storage::exists($path)){
            abort(404);
        }
        return Storage::download($path);
    }

    // Hapus file
    public function delete(Request $request)
    {
        $request->validate(['folder'=>'nullable|string','filename'=>'required|string']);
        $path = $this->baseFolder.($request->folder ? '/'.$request->folder : '').'/'.$request->filename;

        if(Storage::exists($path)){
            Storage::delete($path);
            return response()->json(['success'=>true]);
        }
        return response()->json(['success'=>false,'message'=>'File tidak ditemukan']);
    }


    public function rename(Request $request)
    {
        $request->validate([
            'folder' => 'nullable|string',
            'old_name' => 'required|string',
            'new_name' => 'required|string',
            'type' => 'required|string|in:file,folder'
        ]);

        $oldPath = $this->baseFolder.($request->folder ? '/'.$request->folder : '').'/'.$request->old_name;
        $newPath = $this->baseFolder.($request->folder ? '/'.$request->folder : '').'/'.$request->new_name;

        if(!Storage::exists($oldPath)){
            return response()->json(['success'=>false,'message'=>'File / Folder tidak ditemukan']);
        }

        Storage::move($oldPath, $newPath);
        return response()->json(['success'=>true]);
    }

    public function move(Request $request)
    {
        $request->validate([
            'folder' => 'nullable|string',
            'filename' => 'required|string',
            'destination_folder' => 'required|string',
        ]);

        $oldPath = $this->baseFolder.($request->folder ? '/'.$request->folder : '').'/'.$request->filename;
        $newPath = $this->baseFolder.'/'.$request->destination_folder.'/'.$request->filename;

        if(!Storage::exists($oldPath)){
            return response()->json(['success'=>false,'message'=>'File tidak ditemukan']);
        }

        Storage::move($oldPath, $newPath);
        return response()->json(['success'=>true]);
    }

    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string',
            'folder' => 'nullable|string'
        ]);

        $path = $this->baseFolder.($request->folder ? '/'.$request->folder : '');
        $allFiles = Storage::files($path);
        $allFolders = Storage::directories($path);

        $files = array_filter($allFiles, fn($f) => Str::contains(basename($f), $request->q));
        $folders = array_filter($allFolders, fn($d) => Str::contains(basename($d), $request->q));

        return response()->json(['files'=>array_values($files),'folders'=>array_values($folders)]);
    }

}
