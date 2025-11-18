@extends('admin.layout.app')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card clearfix">
                        <div class="card-header clearfix">
                            <a href="{{ route('employees.create') }}" class="btn btn-sm btn-flat btn-info float-right"> <i
                                    class="fas fa-plus"></i> Tambah</a>
                            <h3 class="card-title">Daftar Pegawai</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            @include('admin.partials.alert')
                            <table id="cmspages" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>NIP</th>
                                        <th>Email</th>
                                        <th>City</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employees as $employee)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $employee->employee_id }}</td>
                                            <td>{{ $employee->email }}</td>
                                            <td>{{ $employee->city }}</td>
                                            <td>{{ $employee->status ? 'Active' : 'Inactive' }}</td>
                                            <td>
                                                <a href="{{ route('employees.edit', $employee) }}"
                                                    class="btn btn-sm btn-warning">Edit</a>
                                                <form action="{{ route('employees.destroy', $employee) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure?')">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
@push('scripts')
    @include('admin.partials._swalDeleteConfirm')
@endpush
