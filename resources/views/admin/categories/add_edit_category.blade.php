@extends('admin.layout.app')

@section('content')
<!-- Main content -->
<section class="content">
      <div class="container-fluid">
        <!-- SELECT2 EXAMPLE -->
        <div class="card card-default">
          <div class="card-header">
            <a href="{{ url('admin/categories') }}" class="btn btn-sm btn-flat btn-info float-right"> <i class="fas fa-solid fa-backward"></i> Kembali</a>
          <h3 class="card-title">{{ $title}}</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
              @include('admin.partials.alert')
                <form name="categoryForm" enctype="multipart/form-data" id="categoryForm" @if(empty($category['id'])) action="{{ url('admin/add-edit-category')}}" @else action="{{ url('admin/add-edit-category/'.$category['id'])}}" @endif method="post">@csrf
                <input type="hidden" id="id" name="id" value="{{ $category['id'] ?? '' }}">
                <div class="form-group">
                  <label>Nama Kategori</label>
                <input type="text" id="category_name" name="category_name" placeholder="Ketikkan Judul Halaman" class="form-control" value="{{ $category['category_name'] ?? '' }}">
                </div>
                <div class="form-group">
                  <label>Kategori Induk (parent)</label>
                  <select class="form-control select2bs4" id="parent_id" name="parent_id" style="width: 100%;">
                    <option>---Pilih Kategori Utama ---</option>
                    <option @if($category['parent_id']==0) selected="selected" @endif value="0">---Kategori Utama ---</option>
                    @foreach($getCategories as $cat)
                    <option @if(isset($category['parent_id'])&&$category['parent_id']==$cat['id']) selected="selected" @endif value="{{ $cat['id']}}">&raquo; {{ $cat['category_name']}}</option>
                    @if(!empty($cat['subcategories']))
                      @foreach($cat['subcategories'] as $subcat)
                        <option @if(isset($category['parent_id'])&&$category['parent_id']==$subcat['id']) selected="selected" @endif value="{{ $subcat['id']}}">&nbsp;&nbsp;&raquo;&raquo; {{ $subcat['category_name']}}</option>
                        @if(!empty($subcat['subcategories']))
                          @foreach($subcat['subcategories'] as $subsubcat)
                            <option @if(isset($category['parent_id'])&&$category['parent_id']==$subsubcat['id']) selected="selected" @endif value="{{ $subsubcat['id']}}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo;&raquo; {{ $subsubcat['category_name']}}</option>
                          @endforeach
                        @endif
                      @endforeach
                     @endif
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label>Foto / Background</label>
                <input type="file" id="category_image" name="category_image" placeholder="Input Foto" class="form-control" value="{{ $category['category_image'] ?? '' }}">
                <br>
                    @if(!empty($category['category_image']))
                    <a target="_blank" href="{{ url('front/images/categories/'.$category['category_image']) }}" >
                    <img style="max-width:150px;" src="{{ asset('front/images/categories/'. $category['category_image'])}}" alt=""></a>
                    <a class="confirmDelete" href="javascript:void(0)" record="category-image" recordid="{{ $category['id'] }}" title="Hapus Foto Kategori"> <i class="fas fa-trash text-danger"></i> </a>
                    <input type="hidden"  name="current_image" value="{{ $category['category_image'] ?? '' }}" class="form-control">
                    @endif
                </div>
                <div class="form-group">
                  @if(empty($category['id']))
                  <button type="submit" class="btn btn-primary">Simpan</button>
                  @else
                  <button type="submit" class="btn btn-info">Update</button>
                  @endif
                </form>
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
</section>
<!-- /.content -->
@endsection


@push('scripts')
@include('admin.partials._swalDeleteConfirm')
@endpush