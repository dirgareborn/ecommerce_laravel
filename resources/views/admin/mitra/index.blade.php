@extends('admin.layout.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5 class="mb-0">Daftar Mitra</h5>
        <a href="{{ route('admin.mitra.create') }}" class="btn btn-primary btn-sm">Tambah Mitra</a>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Logo</th>
                    <th>Nama</th>
                    <th>Status</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mitra as $m)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <img src="{{ asset('uploads/mitra/'.$m->logo) }}" 
                        alt="{{ $m->nama }}" width="70">
                    </td>
                    <td>{{ $m->nama }}</td>
                    <td>
                        @if($m->status)
                        <span class="badge bg-success">Aktif</span>
                        @else
                        <span class="badge bg-secondary">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('admin.mitra.toggle', $m->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')

                            @if($m->status)
                            <button class="btn btn-sm btn-warning">Nonaktifkan</button>
                            @else
                            <button class="btn btn-sm btn-success">Aktifkan</button>
                            @endif
                        </form>
                        <a href="{{ route('admin.mitra.edit', $m->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin.mitra.destroy', $m->id) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Hapus mitra ini?')">
                          @csrf
                          @method('DELETE')
                          <button class="btn btn-danger btn-sm">Hapus</button>
                      </form>
                  </td>
              </tr>
              @empty
              <tr><td colspan="4" class="text-center">Belum ada mitra</td></tr>
              @endforelse
          </tbody>
      </table>
  </div>
</div>
@endsection
