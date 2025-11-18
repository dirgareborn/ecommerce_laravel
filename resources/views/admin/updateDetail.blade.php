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
                <h3 class="card-title">Perbaharui Profil</h3>
              </div>
              <!-- /.card-header -->
             @include('admin.partials.alert')
              <!-- form start -->
              <form method="post" action="{{ route('admin.profil') }}" enctype="multipart/form-data"> @csrf
                <div class="card-body">
                  <div class="form-group">
                    <label for="Email">Email</label>
                    <input type="email" class="form-control" id="admin_email" value="{{ Auth::guard('admin')->user()->email }}" readonly="">
                  </div>
                  <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" id="admin_name" name="admin_name" value="{{ Auth::guard('admin')->user()->name ?? '' }}" class="form-control">
                    <span class="text-danger" id="verifyCurrentPwd"></span>
                  </div>
                  <div class="form-group">
                    <label for="handphone">No. Handphone</label>
                    <input type="text" name="admin_mobile" id="admin_mobile" value="{{ Auth::guard('admin')->user()->mobile ?? '' }}" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="handphone">Image</label>
                    <input type="file" name="admin_image" id="admin_image" value="{{ Auth::guard('admin')->user()->image ?? '' }}" class="form-control">
                    <br>
                    @if(!empty(Auth::guard('admin')->user()->image))
                    <a target="_blank" href="{{ url('admin/images/avatars/'.Auth::guard('admin')->user()->image) }}" > Lihat Foto </a>
                    <input type="hidden"  name="current_image" value="{{ Auth::guard('admin')->user()->image }}" class="form-control">
                    @endif
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