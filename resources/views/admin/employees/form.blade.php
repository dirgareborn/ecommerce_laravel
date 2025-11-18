@extends('admin.layout.app')
@section('content')
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <!-- SELECT2 EXAMPLE -->
    <div class="card card-default">
      <div class="card-header">
        <a href="{{ url('admin/employees') }}" class="btn btn-sm btn-flat btn-info float-right"> <i class="fas fa-solid fa-backward"></i> Kembali</a>
        <h3 class="card-title">{{ $title}}</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="row">
          <div class="col-md-12">
              @include('admin.partials.alert')
              <form action="{{ isset($employee) ? route('employees.update', $employee) : route('employees.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($employee)) @method('PUT') @endif
                <div class="row">
                    <div class="col-md-6">

                        <div class="form-group">
                            <label> Nama</label>
                            <input type="name" class="form-control" name="name" value="{{ old('name', $employee->name ?? '') }}" placeholder="State">
                        </div>
                        <div class="form-group">
                            <label>NIP / ID</label>
                            <input type="text" class="form-control" name="employee_id" value="{{ old('employee_id', $employee->employee_id ?? '') }}" placeholder="NIP" required>
                        </div>
                        <div class="form-group">
                            <label> Email </label>
                            <input type="email" class="form-control" name="email" value="{{ old('email', $employee->email ?? '') }}" placeholder="Email" required>
                        </div>
                        <div class="form-group">
                            <label> Alamat </label>
                            <input type="text" class="form-control" name="address" value="{{ old('address', $employee->address ?? '') }}" placeholder="Address">
                        </div>
                        <div class="form-group">
                            <label> Kota/Kab</label>
                            <input type="text" class="form-control" name="city" value="{{ old('city', $employee->city ?? '') }}" placeholder="City">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label> Provinsi</label>
                            <input type="text" class="form-control" name="state" value="{{ old('state', $employee->state ?? '') }}" placeholder="State">
                        </div>
                        <div class="form-group">
                            <label> Kode Pos </label>
                            <input type="text" class="form-control" name="postal_code" value="{{ old('postal_code', $employee->postal_code ?? '') }}" placeholder="Postal Code">
                        </div>
                        <div class="form-group">
                            <label> Foto </label>
                            @if(isset($employee) && $employee->image)
                            <img src="{{ asset('uploads/employees/' . $employee->image) }}" alt="Employee Image" width="80" class="mb-2">
                            @endif
                            <input type="file" class="form-control" name="image">
                        </div>
                        <div class="form-group">
                            <label> Sosial MEdia </label>
                            <textarea class="form-control" name="sosmed" placeholder='Social Media JSON'>
                                {{ old('sosmed', isset($employee) && $employee->sosmed ? json_encode($employee->sosmed) : '') }}
                            </textarea>
                        </div>
                        <div class="form-group d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input type="hidden" name="status" value="0">
                                <input class="form-check-input" type="checkbox" name="status" value="1" id="statusCheckbox"
                                {{ (old('status', $employee->status ?? 1)) ? 'checked' : '' }}>
                                <label class="form-check-label" for="statusCheckbox">Status</label>
                            </div>

                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>

                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
    </div>
</div>
</div>
<!-- /.card-body -->
</section>
<!-- /.content -->
@endsection