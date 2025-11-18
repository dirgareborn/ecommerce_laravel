@extends('admin.layout.app')

@section('content')
 <!-- Main content -->
 <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
             <!-- general form elements -->
             <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Perbaharui Password</h3>
              </div>
              <!-- /.card-header -->
             @include('admin.partials.alert')
              <!-- form start -->
              <form method="post" action="{{ url('admin/update-password') }}"> @csrf
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Email</label>
                    <input type="email" class="form-control" id="admin_email" value="{{ Auth::guard('admin')->user()->email }}" readonly="">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">kata sandi Lama</label>
                    <input type="password" id="current_pwd" name="current_pwd" class="form-control">
                    <span class="text-danger" id="verifyCurrentPwd"></span>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">kata Sandi Baru</label>
                    <input type="password" name="new_pwd" id="new_pwd" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Konfirmasi kata sandi Baru</label>
                    <input type="password" name="confirm_pwd" id="confirm_pwd" class="form-control">
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
 </section>
@endsection