@extends('admin.layout.app')
@push('style')
<!-- Summernote CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">
<style>
    .note-editor.note-frame {
        border-radius: 0;
    }
    .img-preview {
        width: 200px;
        margin-top: 10px;
        display: none;
    }
</style>
@endpush
@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">{{ $title}}</h3>
        <a href="{{ route('admin.information.index') }}" class="btn btn-secondary mb-3 float-right">
            <i class="fas fa-arrow-left"></i> 
        </a>
    </div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data" action="{{ $info->id ? route('admin.information.update', $info->id) : route('admin.information.store') }}">
            @csrf
            @if($info->id)
            @method('PUT')
            @endif

            <div class="form-group">
                <label>Judul</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $info->title) }}" required>
            </div>
            {{-- Upload Gambar --}}
            <div class="mb-3">
                <label class="form-label">Upload Gambar (Opsional)</label>
                <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(event)">
                <img id="preview" class="img-preview img-fluid rounded">
            </div>

            {{-- Summernote --}}
            <div class="mb-3">
                <label class="form-label">Content Informasi</label>
                <textarea name="content" id="summernote" class="form-control" required>{{ old('content', $info->content) }}</textarea>
            </div>



            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="draft" {{ $info->status=='draft'?'selected':'' }}>Draft</option>
                    <option value="published" {{ $info->status=='published'?'selected':'' }}>Published</option>
                </select>
            </div>

            <div class="form-group">
                <label>Tanggal Terbit</label>
                <input type="date" 
                name="published_at" 
                class="form-control" 
                value="{{ old('published_at', $info->published_at ? \Carbon\Carbon::parse($info->published_at)->format('Y-m-d') : '') }}">

            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
</div>
</section>
@endsection
@push('scripts')

<!-- Summernote JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>

<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            placeholder: 'Tulis isi informasi di sini...',
            tabsize: 2,
            height: 250,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });
    });

        // Preview gambar sebelum upload
    function previewImage(event) {
        let output = document.getElementById('preview');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.style.display = "block";
    }
</script>
@endpush