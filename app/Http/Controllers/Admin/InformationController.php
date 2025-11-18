<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Information;
use Illuminate\Support\Str;
use Auth;

class InformationController extends Controller
{
    public function index()
    {
        $info = Information::latest()->paginate(20);
        return view('admin.information.index', compact('info'));
    }

    public function create()
    {
        $info = new Information;
        return view('admin.information.form', compact('info'))->with('title', 'Tambah Informasi');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'status' => 'required|in:draft,published',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
        ]);
        $content = $request->content;
         // jika upload gambar - simpan lalu sisipkan ke HTML
        if ($request->hasFile('image')) {
            $path = $request->image->store('informasi', 'public');
            $url = asset('storage/' . $path);

            // contoh: gambar disisipkan di akhir
            $content .= "<br><img src='$url' class='img-fluid' />";
        }

// dd($content);
        $data = $request->all();
        $data['slug'] = Str::slug($data['title']);
        $data['admin_id'] = Auth::guard('admin')->id();
        $data['content'] = $content;

        Information::create($data);

        return redirect()->route('admin.information.index')->with('success', 'Informasi berhasil dibuat');
    }

    public function edit($id)
    {
        $info = Information::findOrFail($id);
        return view('admin.information.form', compact('info'))->with('title', 'Edit Informasi');
    }

    public function update(Request $request, $id)
    {
        $info = Information::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($data['title']);
        $info->update($data);

        return redirect()->route('admin.information.index')->with('success', 'Informasi berhasil diperbarui');
    }

    public function destroy($id)
    {
        $info = Information::findOrFail($id);
        $info->delete();
        return redirect()->route('admin.information.index')->with('success', 'Informasi berhasil dihapus');
    }

    public function upload(Request $request)
    {
        if($request->hasFile('upload')) {
            $file = $request->file('upload');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $url = asset('uploads/'.$filename);
            return response()->json(['uploaded'=>1, 'fileName'=>$filename, 'url'=>$url]);
        }
         return response()->json(['uploaded'=>0, 'error'=>['message'=>'Upload gagal']]);
    }
}
