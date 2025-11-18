@extends('front.layouts.app')

@push('style')
<style>
    .doc-list li {
        border-bottom: 1px solid #e5e5e5;
        padding: 15px 0;
    }
    .doc-icon {
        font-size: 22px;
        width: 30px;
    }
</style>
@endpush

@section('content')

@include('front.partials.breadcumb')

<div class="container py-4">
    <h4 class="mb-4 text-uppercase bg-title-orange">Daftar Dokumen</h4>

    @foreach ($groupedDocuments as $type => $documents)
    <!-- Heading berdasarkan type -->
    <h5 class="mt-4 text-primary text-uppercase">{{ $type }}</h5>

    <ul class="list-unstyled doc-list">
        @foreach ($documents as $doc)

        @php
        // Tentukan icon sesuai tipe file
        $ext = strtolower(pathinfo($doc->file_path, PATHINFO_EXTENSION));

        $icon = match($ext) {
            'pdf'       => 'fas fa-file-pdf text-danger',
            'doc', 'docx' => 'fas fa-file-word text-primary',
            'xls', 'xlsx' => 'fas fa-file-excel text-success',
            'ppt', 'pptx' => 'fas fa-file-powerpoint text-warning',
            'jpg','jpeg','png' => 'fas fa-file-image text-info',
            'zip','rar' => 'fas fa-file-archive text-secondary',
            default => 'fas fa-file-alt text-muted'
        };
        @endphp

        <li class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                 @if($doc->created_at >= now()->subDay())
                  <span class="badge fs-7 bg-danger  position-absolute new-badge">
                    New
                </span>
                @endif
                <i class="{{ $icon }} doc-icon me-2"></i>
                <div>

                <strong title="{{ $doc->title }}">{{ \Illuminate\Support\str::limit($doc->title, 100) }}

                </strong>
                <br>
                <small class="text-muted">{{ $doc->created_at->format('d M Y') }}</small>
            </div>
        </div>

        <a href="{{ route('dokumen.preview', $doc->doc_path) }}"
         class="btn btn-sm btn-outline-primary"
         target="_blank">
         <span class="d-none d-sm-inline">Download</span><i class="fas fa-download ms-1"></i>
     </a>
 </li>

 @endforeach
</ul>

@endforeach
</div>

@endsection
