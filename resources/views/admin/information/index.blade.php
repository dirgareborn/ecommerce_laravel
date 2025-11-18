@extends('admin.layout.app')
@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Daftar Informasi</h3>
        <a href="{{ route('admin.information.create') }}" class="btn btn-success mb-3 float-right">Tambah Informasi</a>
    </div>
    <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul</th>
                    <th>Status</th>
                    <th>Admin</th>
                    <th>Tanggal Terbit</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($info as $i)
                <tr>
                    <td>{{ $i->id }}</td>
                    <td>{{ $i->title }}</td>
                    <td>{{ ucfirst($i->status) }}</td>
                    <td>{{ $i->author->name ?? '-' }}</td>
                    <td>{{ format_date($i->published_at ?? '-') }}</td>
                    <td>
                        <div class="btn-group">
                            
                        <a href="{{ route('admin.information.edit', $i->id) }}" class="btn btn-sm btn-primary btn-flat" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form action="{{ route('admin.information.destroy', $i->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger btn-flat" onclick="return confirm('Yakin ingin hapus?')" title="Hapus">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                        </div>

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{ $info->links() }}
    </div>
</div>
</div>
</section>

@endsection
