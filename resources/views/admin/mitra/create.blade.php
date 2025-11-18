@extends('admin.layout.app')

@section('content')
<div class="card">
    <div class="card-header"><h5>Tambah Mitra</h5></div>
    <div class="card-body">

        <form action="{{ route('admin.mitra.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label>Nama Mitra</label>
                <input type="text" name="nama" class="form-control">
            </div>

            <div class="mb-3">
                <label>Logo</label>
                <input type="file" name="logo" class="form-control">
            </div>

            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>
            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.mitra.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
        
    </div>
</div>
@endsection
