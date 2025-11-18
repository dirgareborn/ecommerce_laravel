@extends('admin.layout.app')

@section('content')
<section>
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Tambah Role Admin</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Admin</label>
                    <select name="admin_id" class="form-control select2bs4">
                        @foreach($admins as $admin)
                        <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Modul</label>
                    <select name="module[]" class="form-control select2bs4" multiple>
                        @foreach($modules as $module)
                        <option value="{{ $module }}">{{ $module }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="view_access" class="form-check-input" id="view_access">
                    <label for="view_access" class="form-check-label">View Access</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="edit_access" class="form-check-input" id="edit_access">
                    <label for="edit_access" class="form-check-label">Edit Access</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="full_access" class="form-check-input" id="full_access">
                    <label for="full_access" class="form-check-label">Full Access</label>
                </div>
                <button class="btn btn-sm btn-primary mt-3">Simpan</button>
            </form>
        </div>
    </div>
</div>
</section>
@endsection
