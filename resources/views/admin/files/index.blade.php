@extends('admin.layout.app')

@push('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/min/dropzone.min.css">
<style>
    .file-preview { width: 60px; height: 60px; object-fit: cover; border-radius: 5px; }
    .file-card { display: inline-block; margin: 5px; text-align: center; width: 120px; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <h3>File Manager</h3>

    @include('admin.partials.alert')

    <div class="mb-3">
        <input type="text" id="fileSearch" class="form-control" placeholder="Cari file / folder...">
    </div>

    <div id="file-list" class="d-flex flex-wrap">
        @foreach($folders as $folder)
        @php $folderName = basename($folder); @endphp
        <div class="file-card border p-2">
            <i class="fas fa-folder fa-3x"></i>
            <p class="text-truncate">{{ $folderName }}</p>
            <button class="btn btn-sm btn-primary btn-rename" data-type="folder" data-name="{{ $folderName }}" title="Rename">
        <i class="fas fa-edit"></i> </button>
            <button class="btn btn-sm btn-warning btn-move" title="Move" data-type="folder" data-name="{{ $folderName }}"><i class="fas fa-arrows-alt"></i></button>
        </div>
        @endforeach

        @foreach($files as $file)
        @php $filename = basename($file); @endphp
        <div class="file-card border p-2">
            @if(in_array(pathinfo($filename, PATHINFO_EXTENSION), ['jpg','jpeg','png','gif']))
            <img src="{{ asset('storage/uploads/'.$currentFolder.'/'.$filename) }}" class="file-preview">
            @else
            <i class="fas fa-file-alt fa-3x"></i>
            @endif
            <p class="text-truncate">{{ $filename }}</p>
            <div class="btn-group mt-2">
            <button class="btn btn-sm btn-primary btn-rename" data-type="file" data-name="{{ $filename }}" title="Rename">
        <i class="fas fa-edit"></i></button>
        <button class="btn btn-sm btn-info btn-copy-url" 
                data-url="{{ asset('storage/uploads/'.$currentFolder.'/'.$filename) }}"
                title="Copy URL">
                <i class="fas fa-copy"></i>
            </button>
            <button class="btn btn-sm btn-warning btn-move" data-type="file" data-name="{{ $filename }}" title="Move"><i class="fas fa-arrows-alt"></i></button>
        </div>
    </div>
        @endforeach
    </div>


    <!-- Buat Folder Baru -->
    <form action="{{ route('admin.files.folder') }}" method="POST" class="mb-3 d-flex">
        @csrf
        <input type="hidden" name="parent_folder" value="{{ $currentFolder }}">
        <input type="text" name="folder_name" class="form-control me-2" placeholder="Nama folder Baru" required>
        <button class="btn btn-primary btn-sm">Tambah</button>
    </form>

    <!-- Navigasi Folder -->
    <div class="mb-2">
        <strong>Folder:</strong>
        <a href="{{ route('admin.files.index') }}">/</a>
        @if($currentFolder)
        @php
        $segments = explode('/', $currentFolder);
        $path = '';
        @endphp
        @foreach($segments as $segment)
        @php $path = $path ? $path.'/'.$segment : $segment; @endphp
        / <a href="{{ route('admin.files.index',['folder'=>$path]) }}">{{ $segment }}</a>
        @endforeach
        @endif
    </div>

    <!-- Upload File -->
    <form action="{{ route('admin.files.upload') }}" class="dropzone mb-3" id="file-dropzone">
        @csrf
        <input type="hidden" name="folder" value="{{ $currentFolder }}">
    </form>

    <!-- Daftar Folder -->
    <div class="mb-3">
        @foreach($folders as $folder)
        @php $folderName = basename($folder); @endphp
        <a href="{{ route('admin.files.index',['folder'=>$currentFolder ? $currentFolder.'/'.$folderName : $folderName]) }}" class="btn btn-sm btn-secondary me-1 mb-1">
            <i class="fas fa-folder"></i> {{ $folderName }}
        </a>
        @endforeach
    </div>

    <!-- Daftar File -->
    <div id="file-list" class="d-flex flex-wrap">
        @foreach($files as $file)
        @php $filename = basename($file); @endphp
        <div class="file-card border p-2">
            @if(in_array(pathinfo($filename, PATHINFO_EXTENSION), ['jpg','jpeg','png','gif']))
            <img src="{{ asset('storage/uploads/'.$currentFolder.'/'.$filename) }}" class="file-preview">
            @else
            <i class="fas fa-file-alt fa-3x"></i>
            @endif
            <p class="text-truncate">{{ $filename }}</p>
            <div class="d-flex flex-column">
                <div class="btn-group mt-2">
                    <a href="{{ route('admin.files.download',[$currentFolder,$filename]) }}" 
                    class="btn btn-sm btn-success" title="Download">
                    <i class="fas fa-download"></i>
                </a>
                <button class="btn btn-sm btn-info btn-copy-url" 
                data-url="{{ asset('storage/uploads/'.$currentFolder.'/'.$filename) }}"
                title="Copy URL">
                <i class="fas fa-copy"></i>
            </button>
            <button class="btn btn-sm btn-danger btn-delete" data-filename="{{ $filename }}" title="Hapus">
                <i class="fas fa-trash"></i>
            </button>
        </div>

    </div>
</div>
@endforeach
</div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/min/dropzone.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Dropzone.autoDiscover = false;
    let myDropzone = new Dropzone("#file-dropzone", {
        paramName: "files", 
        maxFilesize: 10,
        uploadMultiple: true,
        parallelUploads: 5,
        addRemoveLinks: false,
        success: function(file, response){
            fetchFiles();
            this.removeFile(file);
        }
    });

    function fetchFiles(){
        $.get("{{ route('admin.files.index',['folder'=>$currentFolder]) }}", function(html){
            let newFiles = $(html).find('#file-list').html();
            $('#file-list').html(newFiles);
            attachDeleteEvent();
        });
    }

    function attachDeleteEvent(){
        $('.btn-delete').off('click').on('click', function(){
            let filename = $(this).data('filename');
            Swal.fire({
                title: 'Hapus file ini?',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                icon: 'warning'
            }).then((result)=>{
                if(result.isConfirmed){
                    $.ajax({
                        url: "{{ route('admin.files.delete') }}",
                        type: 'DELETE',
                        data: { filename: filename, folder: "{{ $currentFolder }}", _token: "{{ csrf_token() }}" },
                        success: function(res){
                            if(res.success){
                                Swal.fire('Terhapus!', '', 'success');
                                fetchFiles();
                            }else{
                                Swal.fire('Error', res.message, 'error');
                            }
                        }
                    });
                }
            });
        });
    }

    attachDeleteEvent();

// Rename
    $(document).on('click','.btn-rename',function(){
        let type = $(this).data('type');
        let oldName = $(this).data('name');
        Swal.fire({
            title: 'Rename '+type,
            input: 'text',
            inputValue: oldName,
            showCancelButton: true
        }).then((result)=>{
            if(result.isConfirmed){
                $.post("{{ route('admin.files.rename') }}",{
                    _token: "{{ csrf_token() }}",
                    folder: "{{ $currentFolder }}",
                    old_name: oldName,
                    new_name: result.value,
                    type: type
                },function(res){
                    if(res.success) location.reload();
                });
            }
        });
    });

// Move
    $(document).on('click','.btn-move',function(){
        let name = $(this).data('name');
        Swal.fire({
            title: 'Pindahkan ke folder',
            input: 'text',
            inputPlaceholder: 'Masukkan nama folder tujuan',
            showCancelButton:true
        }).then((result)=>{
            if(result.isConfirmed){
                $.post("{{ route('admin.files.move') }}",{
                    _token: "{{ csrf_token() }}",
                    folder: "{{ $currentFolder }}",
                    filename: name,
                    destination_folder: result.value
                },function(res){
                    if(res.success) location.reload();
                });
            }
        });
    });

// Search
    $('#fileSearch').on('input', function(){
        let query = $(this).val();
        if(query.length < 2) return;
        $.get("{{ route('admin.files.search') }}", {q: query, folder: "{{ $currentFolder }}"}, function(res){
            let html = '';
            res.folders.forEach(f=>{
                let folderName = f.split('/').pop();
                html += `<div class="file-card border p-2"><i class="fas fa-folder fa-3x"></i><p>${folderName}</p></div>`;
            });
            res.files.forEach(f=>{
                let fileName = f.split('/').pop();
                html += `<div class="file-card border p-2"><i class="fas fa-file-alt fa-3x"></i><p>${fileName}</p></div>`;
            });
            $('#file-list').html(html);
        });
    });
// Copy URL ke clipboard
    $(document).on('click', '.btn-copy-url', function() {
        let url = $(this).data('url');
        navigator.clipboard.writeText(url).then(function() {
            Swal.fire({
                icon: 'success',
                title: 'URL disalin!',
                text: url,
                toast: true,
                position: 'top-end',
                timer: 2000,
                showConfirmButton: false
            });
        }).catch(function(err){
            Swal.fire('Error', 'Gagal menyalin URL', 'error');
        });
    });

</script>
@endpush
