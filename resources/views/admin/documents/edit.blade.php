@extends('admin.layout.app')
@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">{{ $title ?? 'Edit Dokumen'}}</h3>
        <a href="{{ route('admin.document.index') }}" class="btn btn-secondary mb-3 float-right">
            <i class="fas fa-arrow-left"></i> 
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.document.update', $document->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')


            <div class="mb-3">
                <label>Judul</label>
                <input type="text" name="title" class="form-control" value="{{ $document->title }}" required>
            </div>


            <div class="mb-3">
                <label>Tipe Dokumen</label>
                <select name="type" class="form-control" required>
                    @foreach(['umum','sk','sop','pmk','formulir'] as $t)
                    <option value="{{ $t }}" {{ $document->type==$t?'selected':'' }}>{{ strtoupper($t) }}</option>
                    @endforeach
                </select>
            </div>


            <div class="mb-3">
                <label>File Sekarang</label><br>
                <small>{{ $document->doc_path }}</small>
            </div>


            <div class="mb-3">
                <label>Upload File Baru (Opsional)</label>
                <input type="file" name="doc_path" class="form-control">
            </div>


            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="1" {{ $document->status?'selected':'' }}>Aktif</option>
                    <option value="0" {{ !$document->status?'selected':'' }}>Nonaktif</option>
                </select>
            </div>


            <button class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
</div>
</section>

@endsection