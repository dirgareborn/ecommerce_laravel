@extends('admin.layout.app')

@push('style')
    <style>
        .field_wrapper {
            display: none;
        }

        .form-attribute {
            width: 20%;
            padding: .375rem .75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            border: 1px solid #ced4da;
            border-radius: .25rem;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        .form-attribute:focus {
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 .2rem rgba(0, 123, 255, .25);
        }

        .step {
            display: none;
        }

        .step.active {
            display: block;
        }

        .step-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .step-buttons button {
            min-width: 120px;
        }
    </style>
@endpush

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <a href="{{ url('admin/products') }}" class="btn btn-sm btn-flat btn-info float-right">
                        <i class="fas fa-solid fa-backward"></i> Kembali
                    </a>
                    <h3 class="card-title">{{ $title }}</h3>
                </div>

                <div class="card-body">
                    @include('admin.partials.alert')

                    <form id="productForm" name="productForm" enctype="multipart/form-data"
                        @if (empty($product['id'])) action="{{ url('admin/add-edit-product') }}"
        @else action="{{ url('admin/add-edit-product/' . $product['id']) }}" @endif
                        method="post">
                        @csrf

                        <input type="hidden" id="id" name="id" value="{{ $product['id'] ?? '' }}">

                        {{-- STEP 1: INFORMASI UTAMA --}}
                        <div class="step active" id="step-1">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Nama Produk</label>
                                        <input type="text" name="product_name" class="form-control"
                                            placeholder="Ketikkan Nama Produk" value="{{ $product['product_name'] ?? '' }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Kategori Produk</label>
                                        <select class="form-control select2bs4" id="category_id" name="category_id">
                                            <option value="0">--- Pilih Kategori ---</option>
                                            @foreach ($getCategories as $cat)
                                                <option @selected(isset($product['category_id']) && $product['category_id'] == $cat['id']) value="{{ $cat['id'] }}">
                                                    &raquo; {{ $cat['category_name'] }}
                                                </option>
                                                @if (!empty($cat['subcategories']))
                                                    @foreach ($cat['subcategories'] as $subcat)
                                                        <option @selected(isset($product['category_id']) && $product['category_id'] == $subcat['id']) value="{{ $subcat['id'] }}">
                                                            &nbsp;&nbsp;&raquo;&raquo; {{ $subcat['category_name'] }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Lokasi</label>
                                        <select class="form-control select2bs4" name="location_id">
                                            <option>--- Pilih Lokasi ---</option>
                                            @foreach ($getLocations as $loc)
                                                <option @selected(isset($product['location_id']) && $product['location_id'] == $loc['id']) value="{{ $loc['id'] }}">
                                                    {{ $loc['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Harga</label>
                                        <input type="text" name="product_price" class="form-control"
                                            placeholder="Ketikkan Harga" value="{{ $product['product_price'] ?? '' }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Type Diskon</label>
                                        <select class="form-control select2bs4" id="discount_type" name="discount_type">
                                            <option value="">--- Pilih Type Diskon ---</option>
                                            <option value="">Tidak ada diskon</option>
                                            <option value="percent">Persen (%)</option>
                                            <option value="fixed">Nominal (Rp)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Diskon</label>
                                        <input type="text" name="product_discount" class="form-control"
                                            placeholder="Ketikkan Besaran Diskon"
                                            value="{{ $product['product_discount'] ?? '' }}">
                                    </div>
                                </div>

                                <div
                                    class="col-md-12 mb-2 mt-2 p-2 border-top border-bottom d-flex justify-content-start align-items-center">

                                    <input type="checkbox" class="me-2" value="1" name="is_price_per_type">

                                    <label class="p-2 me-2"> Harga berdasarkan type pengguna</label>

                                </div>
                                <div class="col-md-12 field_wrapper">
                                    <div>
                                        <select class="form-attribute" name="customer_type[]">
                                            <option>---Pilih Type Pengguna---</option>
                                            <option value="umum">Umum</option>
                                            <option value="civitas">Civitas</option>
                                            <option value="mahasiswa">Mahasiswa</option>
                                        </select>
                                        <input class="form-attribute" type="text" placeholder="Harga" name="price[]">
                                        <a href="javascript:void(0);"
                                            class="add_button btn btn-info btn-flat btn-sm">Tambah</a>
                                    </div>
                                </div>
                            </div>

                            <div class="step-buttons">
                                <button type="button" class="btn btn-primary next-step">Lanjut</button>
                            </div>
                        </div>

                        {{-- STEP 2: GAMBAR & MEDIA --}}
                        <div class="step" id="step-2">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Cover Produk</label>
                                        <input type="file" name="cover_image" class="form-control">
                                        <br>
                                        @if (!empty($product['product_image']))
                                            <a target="_blank"
                                                href="{{ asset('storage/uploads/products/' . $product['product_image']) }}">
                                                <img style="max-width:150px;"
                                                    src="{{ asset('storage/uploads/products/' . $product['product_image']) }}">
                                            </a>
                                            <a class="confirmDelete" href="javascript:void(0)" record="product-image"
                                                recordid="{{ $product['id'] }}">
                                                <i class="fas fa-trash text-danger"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="slide_images">Foto Slide</label>
                                        <input type="file" id="slide_images" name="slide_images[]"
                                            class="form-control" multiple="" placeholder="Input Foto">
                                        <br>
                                        @forelse($productSlide as $slide)
                                            <div class="mb-2 d-flex align-items-center">
                                                <a target="_blank"
                                                    href="{{ asset('storage/uploads/products/slide/' . $slide->image) }}">
                                                    <img style="max-width:150px;"
                                                        src="{{ asset('storage/uploads/products/slide/' . $slide->image) }}"
                                                        alt="Slide Image">
                                                </a>
                                                <a href="javascript:void(0)" class="confirmDelete ms-2"
                                                    record="product-image-slide" recordid="{{ $slide->id }}"
                                                    title="Hapus Foto Slide">
                                                    <i class="fas fa-trash text-danger"></i>
                                                </a>
                                                <input type="hidden" name="current_slide_image[]"
                                                    value="{{ $slide->image }}">
                                            </div>
                                        @empty
                                            <p>Tidak ada foto slide.</p>
                                        @endforelse
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Fasilitas</label>
                                            @php
                                                $facilityList = collect($product->product_facility)
                                                    ->pluck('name')
                                                    ->implode(', ');
                                            @endphp

                                            <textarea name="product_facility" class="form-control">{{ $facilityList }}</textarea>

                                        </div>
                                    </div>
                                </div>


                                <div class="step-buttons">
                                    <button type="button" class="btn btn-secondary prev-step">Kembali</button>
                                    <button type="submit" class="btn btn-success">Simpan</button>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            let currentStep = 1;
            const totalSteps = document.querySelectorAll('.step').length;

            function showStep(step) {
                document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
                document.getElementById('step-' + step).classList.add('active');
            }

            // Next button
            document.querySelectorAll('.next-step').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (currentStep < totalSteps) {
                        currentStep++;
                        showStep(currentStep);
                    }
                });
            });

            // Prev button
            document.querySelectorAll('.prev-step').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (currentStep > 1) {
                        currentStep--;
                        showStep(currentStep);
                    }
                });
            });

        });
        $(document).ready(function() {

            // Toggle field_wrapper based on checkbox
            $('input[name="is_price_per_type"]').on('change', function() {
                if ($(this).is(':checked')) {
                    $('.field_wrapper').slideDown();
                } else {
                    $('.field_wrapper').slideUp();
                }
            });

            // Jika edit mode dan sudah ada data, otomatis tampilkan
            @if (!empty($product['is_price_per_type']) && $product['is_price_per_type'] == 1)
                $('.field_wrapper').show();
                $('input[name="is_price_per_type"]').prop('checked', true);
            @endif

            // Dynamic price fields
            var maxField = 5;
            var addButton = $('.add_button');
            var wrapper = $('.field_wrapper');
            var fieldHTML = `
        <div>
            <select class="form-attribute" name="customer_type[]">
                <option>---Pilih Type Pengguna---</option>
                <option value="umum">Umum</option>
                <option value="civitas">Civitas</option>
                <option value="mahasiswa">Mahasiswa</option>
            </select>
            <input class="form-attribute" type="text" name="price[]" placeholder="Harga">
            <a href="javascript:void(0);" class="remove_button btn btn-danger btn-flat btn-sm">Hapus</a>
        </div>
    `;

            var x = 1;
            $(addButton).click(function() {
                if (x < maxField) {
                    x++;
                    $(wrapper).append(fieldHTML);
                } else {
                    alert('Maksimal ' + maxField + ' field tambahan.');
                }
            });

            $(wrapper).on('click', '.remove_button', function(e) {
                e.preventDefault();
                $(this).parent('div').remove();
                x--;
            });
        });
    </script>
    @include('admin.partials._swalDeleteConfirm')
@endpush
