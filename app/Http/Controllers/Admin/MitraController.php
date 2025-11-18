<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Mitra;
use Illuminate\Support\Facades\File;
use App\Http\Requests\MitraStoreRequest;
use App\Http\Requests\MitraUpdateRequest;

class MitraController extends Controller
{
    public function index()
    {
        $mitra = Mitra::latest()->get();
        return view('admin.mitra.index', compact('mitra'));
    }

    public function create()
    {
        return view('admin.mitra.create');
    }

    public function store(MitraStoreRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/mitra'), $filename);
            $data['logo'] = $filename;
        }
        Mitra::create($data);

        return redirect()->route('admin.mitra.index')->with('success', 'Mitra berhasil ditambahkan');
    }

    public function edit(Mitra $mitra)
    {
        return view('admin.mitra.edit', compact('mitra'));
    }

    public function update(MitraUpdateRequest $request, Mitra $mitra)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            if ($mitra->logo && File::exists(public_path('uploads/mitra/'.$mitra->logo))) {
                File::delete(public_path('uploads/mitra/'.$mitra->logo));
            }

            $file = $request->file('logo');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/mitra'), $filename);

            $data['logo'] = $filename;
        }

        $mitra->update($data);

        return redirect()->route('admin.mitra.index')->with('success', 'Mitra berhasil diperbarui');
    }

    public function destroy(Mitra $mitra)
    {
        if ($mitra->logo && File::exists(public_path('uploads/mitra/'.$mitra->logo))) {
            File::delete(public_path('uploads/mitra/'.$mitra->logo));
        }

        $mitra->delete();

        return redirect()->route('admin.mitra.index')->with('success', 'Mitra berhasil dihapus');
    }

    public function toggleStatus(Mitra $mitra)
    {
        $mitra->status = !$mitra->status;
        $mitra->save();

        return back()->with('success', 'Status mitra diperbarui.');
    }
}
