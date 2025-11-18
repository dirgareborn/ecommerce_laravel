@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-header"><h5>Edit Mitra</h5></div>
    <div class="card-body">

        <form action="{{ route('mitra.update', $mitra->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Nama Mitra</label>
                <input type="text" name="nama" value="{{ $mitra->nama }}" class="form-control">
            </div>

            <div class="mb-3">
                <label>Logo Sekarang</label><br>
                <img src="{{ asset('uploads/mitra/'.$mitra->logo) }}"
                alt="{{ $mitra->nama }}" width="90">
            </div>

            <div class="mb-3">
                <label>Ganti Logo (opsional)</label>
                <input type="file" name="logo" class="form-control">
            </div>
            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="1" {{ $mitra->status ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ !$mitra->status ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <button class="btn btn-primary">Update</button>
            <a href="{{ route('mitra.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
        
    </div>
</div>
@endsection
