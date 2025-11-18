@extends('admin.layout.app')
@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">{{ $title ?? 'Tambah Dokumen'}}</h3>
        <a href="{{ route('admin.document.index') }}" class="btn btn-secondary mb-3 float-right">
            <i class="fas fa-arrow-left"></i> 
        </a>
    </div>
    <div class="card-body">
        @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        <form action="{{ route('admin.document.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label>Judul</label>
                <input type="text" name="title" class="form-control" required>
            </div>


            <div class="mb-3">
                <label>Tipe Dokumen</label>
                <select name="type" class="form-control" required>
                    @foreach(['umum','sk','sop','pmk','formulir'] as $t)
                    <option value="{{ $t }}">{{ strtoupper($t) }}</option>
                    @endforeach
                </select>
            </div>


            <div class="mb-3">
                <label>Upload Dokumen</label>
                <input type="file" name="doc_path" class="form-control" required>
            </div>


            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>


            <button class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
</div>
</section>

@endsection