@extends('admin.layout.app')

@section('content')
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card clearfix">
              <div class="card-header clearfix">
                <h3 class="card-title">Daftar Pelanggan (Customer)</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              @include('admin.partials.alert')
                <table id="cmspages" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Tanggal</th>
                    <!-- <th>Aksi</th> -->
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($users as $user)
                  <tr>
                    <td>{{ $user['id'] }}</td>
                    <td>{{ $user['name'] }}</td>
                    <td>{{ $user['email'] }}</td>
                    <td>{{ date("j F Y", strtotime($user['created_at'])); }}</td>
					          <!-- <td><a href="{{ url('admin/add-edit-user/'.$user['id']) }}"> <i class="fas fa-edit text-info"></i> </a></td> -->
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