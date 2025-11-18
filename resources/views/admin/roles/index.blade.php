@extends('admin.layout.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Manajemen Role Admin</h3>
                <a href="{{ route('admin.roles.create') }}" class="btn btn-sm btn-success">Tambah Role</a>
            </div>
            <div class="card-body">
                @include('admin.partials.alert')
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Admin</th>
                            <th>Modul</th>
                            <th>View</th>
                            <th>Edit</th>
                            <th>Full</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr>
                                <td>{{ $role->admin->name }}</td>
                                <td>{{ is_array($role->module) ? implode(', ', $role->module) : $role->module }}</td>
                                <td>{{ $role->view_access ? 'Ya' : '-' }}</td>
                                <td>{{ $role->edit_access ? 'Ya' : '-' }}</td>
                                <td>{{ $role->full_access ? 'Ya' : '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-sm btn-primary"><i
                                            class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Yakin ingin hapus?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
