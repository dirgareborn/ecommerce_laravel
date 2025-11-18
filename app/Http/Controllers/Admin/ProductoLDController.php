<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminRole;
use Intervention\Image\Laravel\Facades\Image;
use App\Models\Category;
use Illuminate\Support\Facades\Session;
use App\Models\Location;
use App\Models\ProductsImage;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Cart;

class ProductController extends Controller
{
	
    /**
     * Display a listing of the resource.
     */
    public function products()
    {
        $title = "Produk";
        $products = Product::with(['locations','category'])->get()->toArray();

        //Set Admin/Subadmins Permissions 
        $productsModuleCount = AdminRole::where(['admin_id'=>Auth::guard('admin')->user()->id,'module'=>'products'])->count();
        $productModule = [];
        if(Auth::guard('admin')->user()->type=="admin"){
            $productModule['view_access']=1;
            $productModule['edit_access']=1;
            $productModule['full_access']=1;
        }else if($productsModuleCount == 0){
            $message = "This Featu is retriced for you!";
            return redirect('admin/dashboard')->with('error_message',$message);
        }else{
            $productModule = AdminRole::where(['admin_id'=>Auth::guard('admin')->user()->id,'module'=>'products'])->first()->toArray();
        }        
        return view('admin.products.products')->with(compact('title','products','productModule'));
    }

    /**
     * Update Status Product the specified resource in storage.
     */
    public function updateStatus(Request $request, Product $product)
    {
        if($request->ajax()){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if($data['status']=="Active"){
                $status = 0;
            }else{
                $status = 1;
            }
            Product::where('id',$data['product_id'])->update(['status'=>$status]);
            return response()->json(['status'=>$status,'product_id'=>$data['product_id']]);
        }
    }

    public function deleteProduct($id)
    {
        Product::where('id',$id)->delete();
        return redirect()->back()->with('success_message','Produk Berhasil dihapus');
    }
    public function deleteProductImage($id)
    {
        $productImage = Product::select('product_image')->where('id',$id)->first();
        $product_image_path = 'front/images/products/';
        if(file_exists($product_image_path.$productImage->product_image)){
            unlink($product_image_path.$productImage->product_image);
        }
        Product::where('id',$id)->update(['product_image'=>'']);
        return redirect()->back()->with('success_message','Foto Produk  Berhasil dihapus');
    }
    public function deleteProductImageSlide($id)
    {
        $productImage = ProductsImage::select('image')->where('id',$id)->first();
        $product_image_path = 'front/images/products/galery/';
        if(file_exists($product_image_path.$productImage->image)){
            unlink($product_image_path.$productImage->image);
        }
        ProductsImage::where('id',$id)->update(['image'=>'']);
        return redirect()->back()->with('success_message','Foto Produk  Berhasil dihapus');
    }

   public function edit(Request $request, $id = null)
    {
        $getCategories = Category::getCategories();
        $getLocations  = Location::all();

        if ($id === null) {
            $title = "Tambah Produk";
            $product = new Product;
            $message = "Produk berhasil ditambahkan";
            $productSlide = collect();
        } else {
            $title = "Ubah Produk";
            $product = Product::findOrFail($id);
            $productSlide = ProductsImage::where('product_id', $id)->get();
            $message = "Produk berhasil diperbaharui";
        }

        if ($request->isMethod('post')) {
            $data = $request->all();

            // Validasi input
            $rules = [
                'category_id' => 'required',
                'product_name' => 'required|string|max:200',
                'product_price' => 'required|numeric|min:0',
                'product_description' => 'required|string',
                'customer_type.*' => 'nullable|in:civitas,mahasiswa,umum',
                'price.*' => 'nullable|numeric|min:0',
            ];

            $customMessages = [
                'product_name.required' => "Judul harus diisi",
                'product_price.required' => "Harga Produk harus diisi",
                'product_description.required' => 'Deskripsi harus diisi',
                'customer_type.*.in' => 'Tipe pengguna tidak valid',
                'price.*.numeric' => 'Harga harus berupa angka',
            ];

            $this->validate($request, $rules, $customMessages);

            // Upload cover image
            if ($request->hasFile('cover_image')) {
                $file = $request->file('cover_image');
                $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)
                            . '-' . date('his') . '-' . Str::random(3)
                            . '.' . $file->getClientOriginalExtension();
                $file->move('front/images/products/', $fileName);
                $product->product_image = $fileName;
            } elseif (!empty($data['current_image'])) {
                $product->product_image = $data['current_image'];
            } else {
                $product->product_image = null;
            }

            // Diskon
            if ($data['discount_type'] === "percent") {
                $product->discount_type = "percent";
                $product->final_price = $data['product_price'] - ($data['product_price'] * $data['product_discount'] / 100);
            } elseif ($data['discount_type'] === "nominal") {
                $product->discount_type = "nominal";
                $product->final_price = $data['product_price'] - $data['product_discount'];
            } 

            // Simpan data produk
            $product->fill([
                'category_id' => $data['category_id'],
                'location_id' => $data['location_id'],
                'product_name' => $data['product_name'],
                'product_facility' => $data['product_facility'] ?? null,
                'product_price' => $data['product_price'],
                'product_discount' => $data['product_discount'] ?? 0,
                'product_description' => $data['product_description'],
                'meta_title' => $data['meta_title'] ?? null,
                'meta_description' => $data['meta_description'] ?? null,
                'meta_keywords' => $data['meta_keywords'] ?? null,
                'status' => 1
            ]);
            $product->save();

            $product_id = $id ?? $product->id;

            // Upload slide images
            if ($request->hasFile('slide_images')) {
                foreach ($request->file('slide_images') as $file) {
                    $fileName = Str::random(5) . '-' . date('his') . '-' . Str::random(3)
                                . '.' . $file->getClientOriginalExtension();
                    $file->move('front/images/products/galery/', $fileName);

                    ProductsImage::create([
                        'product_id' => $product_id,
                        'image' => $fileName,
                        'image_sort' => 0,
                        'status' => 1,
                    ]);
                }
            }

            // Simpan harga khusus customer type, hanya jika lebih dari satu
            if (!empty($data['price_detail'])) {
                // Hapus atribut lama dulu
                ProductAttribute::where('product_id', $product_id)->delete();

                foreach ($data['price_detail'] as $key => $value) {
                    $customerType = $data['customer_type'][$key] ?? null;
                    $price = $data['price'][$key] ?? 0;

                    // Skip jika customer type tidak valid atau kosong
                    if (!in_array($customerType, ['civitas', 'mahasiswa', 'umum'])) {
                        continue;
                    }

                    ProductAttribute::create([
                        'product_id' => $product_id,
                        'customer_type' => $customerType,
                        'price' => $price,
                        'status' => 1,
                    ]);
                }
            }

            return redirect('admin/products')->with('success_message', $message);
        }

        return view('admin.products.add_edit_product', compact('title', 'product', 'getCategories', 'getLocations', 'productSlide'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
