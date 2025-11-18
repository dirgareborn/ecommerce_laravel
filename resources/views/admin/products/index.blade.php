@extends('admin.layout.app')

@section('content')
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card clearfix">
          <div class="card-header clearfix">
            @if($productModule['edit_access']== 1 || $productModule['full_access']==1)
            <a href="{{ url('admin/add-edit-product') }}" class="btn btn-sm btn-flat btn-info float-right"> <i class="fas fa-plus"></i> Tambah</a>
            @endif
            <h3 class="card-title">{{ $title }}</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            @include('admin.partials.alert')
            <table id="cmspages" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nama Produk</th>
                  <th>Kategori</th>
                  <th>Harga</th>
                  <th>Lokasi</th>
                  <th>Tanggal</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($products as $product)
                <tr>
                  <td>{{ $product['id'] }}</td>
                  <td>{{ $product['product_name'] }}</td>
                  
                  <td>
                    @if(isset($product['category_id']))
                    {{ $product['category']['category_name'] ?? '' }}
                    @endif
                  </td>
                  <td>
                    @currency($product['product_price'])
                    
                  </td>
                  <td>
                    @if(isset($product['location_id']))
                    {{ $product['locations']['name'] }}
                    @endif
                  </td>
                  <td>{{ date("j F Y", strtotime($product['created_at'])); }}</td>                    <td>
                    <a href="{{ url('admin/add-edit-product/'.$product['id']) }}"> <i class="fas fa-edit text-info"></i> </a>
                    @if($product['status']==1)
                    <a class="updateProductStatus" id="product-{{ $product['id'] }}" product_id="{{ $product['id'] }}" href="javascrip:void(0)"><i class="fas fa-toggle-on" status="Active"></i></a>
                    @else
                    <a class="updateProductStatus" id="product-{{ $product['id'] }}" product_id="{{ $product['id'] }}" href="javascrip:void(0)" style="color:grey"><i class="fas fa-toggle-off" status="Inactive"></i></a>
                    @endif
                    <a class="confirmDelete" name="{{ $product['product_name'] }}" href="javascript:void(0)" record="product" recordid="{{ $product['id'] }}" title="Hapus Kategori"> <i class="fas fa-trash text-danger"></i> </a>
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