@extends('admin.layout.app')

@section('content')
<section>
    <div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Role Admin</h3>
        </div>
         <div class="card-body">


<form action="{{ route('roles.update', $role->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label>Admin</label>
        <select name="admin_id" class="form-control">
            @foreach($admins as $admin)
                <option value="{{ $admin->id }}" {{ $role->admin_id == $admin->id ? 'selected' : '' }}>
                    {{ $admin->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Modul</label>
        <select name="module[]" class="form-control" multiple>
            @foreach($modules as $module)
                <option value="{{ $module }}" {{ in_array($module, $role->module ?? []) ? 'selected' : '' }}>
                    {{ $module }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-check">
        <input type="checkbox" name="view_access" value="1" {{ $role->view_access ? 'checked' : '' }}> View
        <input type="checkbox" name="edit_access" value="1" {{ $role->edit_access ? 'checked' : '' }}> Edit
        <input type="checkbox" name="full_access" value="1" {{ $role->full_access ? 'checked' : '' }}> Full
    </div>

    <button type="submit" class="btn btn-primary mt-2">Update</button>
</form>
        </div>

    </div>
</div>
</section>

@endsection
