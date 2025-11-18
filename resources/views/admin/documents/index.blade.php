@extends('admin.layout.app')
@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Manajemen Dokumen</h3>
        <a href="{{ route('admin.document.create') }}" class="btn btn-success mb-3 float-right">Upload</a>
    </div>
    <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
    <form method="GET" class="row mb-3">
        <div class="col-md-3">
            <input type="text" name="q" class="form-control" placeholder="Cari data..." value="{{ request('q') }}">
        </div>
        <div class="col-md-3">
            <select name="type" class="form-control">
                <option value="">-- Filter Tipe --</option>
                @foreach(['umum','sk','sop','pmk','formulir'] as $t)
                <option value="{{ $t }}" {{ request('type')==$t ? 'selected':'' }}>{{ strtoupper($t) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-control">
                <option value="">-- Status --</option>
                <option value="1" {{ request('status')==='1'?'selected':'' }}>Aktif</option>
                <option value="0" {{ request('status')==='0'?'selected':'' }}>Nonaktif</option>
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Tipe</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($documents as $doc)
            <tr>
                <td>{{ $doc->title }}</td>
                <td>{{ strtoupper($doc->type) }}</td>
                <td>
                    @if($doc->status)
                    <span class="badge bg-success">Aktif</span>
                    @else
                    <span class="badge bg-secondary">Nonaktif</span>
                    @endif
                </td>
                <td>
                   <div class="btn-group" role="group">

    <a href="{{ route('admin.document.preview', $doc->id) }}" 
       target="_blank"
       class="btn btn-outline-secondary btn-flat btn-sm"
       title="Preview">
        <i class="fas fa-eye"></i>
    </a>

    <a href="{{ route('admin.document.download', $doc->id) }}"
       class="btn btn-outline-info btn-sm"
       title="Download">
        <i class="fas fa-download"></i>
    </a>

    <a href="{{ route('admin.document.edit', $doc->id) }}"
       class="btn btn-outline-warning btn-sm"
       title="Edit">
        <i class="fas fa-edit"></i>
    </a>

    <form action="{{ route('admin.document.destroy', $doc->id) }}" 
          method="POST" 
          class="d-inline">
        @csrf
        @method('DELETE')
        <button onclick="return confirm('Hapus?')" 
                class="btn btn-outline-danger btn-flat btn-sm"
                title="Hapus">
            <i class="fas fa-trash"></i>
        </button>
    </form>

</div>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>


    {{ $documents->links() }}
</div>
</div>
</div>
</section>

@endsection