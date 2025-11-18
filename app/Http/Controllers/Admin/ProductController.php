<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductAttribute;
use App\Models\Location;
use App\Models\AdminRole;
use App\Models\ProductsImage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index()
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
        return view('admin.products.index')->with(compact('title','products','productModule'));
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

    public function edit(Request $request, $id = null)
    {
        if ($id == null) {
            $title = "Tambah Produk";
            $product = new Product;
        } else {
            $title = "Edit Produk";
            $product = Product::findOrFail($id);
        }

        if ($request->isMethod('post')) {
            $data = $request->all();

            // VALIDASI
            $request->validate([
                'product_name' => 'required',
                'product_price' => 'required|numeric',
            ]);

            // ASSIGN FIELD
            $product->product_name = $data['product_name'];
            $product->category_id = $data['category_id'];
            $product->location_id = $data['location_id'];
            $product->product_price = $data['product_price'];
            $product->discount_type = $data['discount_type'] ?? null;
            $product->product_discount = $data['product_discount'] ?? null;

            // SIMPAN COVER IMAGE
            if ($request->hasFile('cover_image')) {
                $img = $request->file('cover_image');
                $ext = $img->getClientOriginalExtension();
                $fileName = Str::random(16) . '.' . $ext;
                $img->storeAs('uploads/products', $fileName, 'public'); // storage/app/public/uploads/products, $fileName);
                $product->product_image = $fileName;
            }

            // SIMPAN FASILITAS (textarea -> json)
            if (!empty($data['product_facility'])) {
                $facilityArray = preg_split('/,\s*/', trim($data['product_facility']));
                $product->product_facility = collect($facilityArray)->map(fn($x) => ['name' => $x])->toArray();
            } else {
                $product->product_facility = [];
            }

            $product->save();

            // ================================
            //   PRODUCT ATTRIBUTE (PRICE PER TYPE)
            // ================================
            ProductAttribute::where('product_id', $product->id)->delete();

            if ($request->is_price_per_type == 1) {
                // MODE DINAMIS
                if (!empty($data['customer_type'])) {
                    foreach ($data['customer_type'] as $i => $ctype) {
                        if ($ctype && $data['price'][$i]) {
                            ProductAttribute::create([
                                'product_id' => $product->id,
                                'customer_type' => $ctype,
                                'price' => $data['price'][$i],
                            ]);
                        }
                    }
                }
            } else {
                // MODE NORMAL (3 DEFAULT TYPE)
                foreach (['umum','civitas','mahasiswa'] as $t) {
                    ProductAttribute::create([
                        'product_id' => $product->id,
                        'customer_type' => $t,
                        'price' => $product->product_price,
                    ]);
                }
            }

            // ================================
            //   SLIDE IMAGES
            // ================================
            if ($request->hasFile('slide_images')) {
                foreach ($request->file('slide_images') as $sl) {
                    $ext = $sl->getClientOriginalExtension();
                    $fileName = Str::random(20) . '.' . $ext;
                    $sl->storeAs('uploads/products/slide', $fileName, 'public'); // storage/app/public/uploads/products/slide, $fileName);
                    ProductsImage::create([
                        'product_id' => $product->id,
                        'image_sort' => 0,
                        'image' => $fileName,
                        'status' => 1,
                    ]);
                }
            }

            return redirect('admin/products')->with('success', 'Data Produk berhasil disimpan');
        }

        // GET DATA UNTUK EDIT
        $productSlide = ProductsImage::where('product_id', $id)->get();
        $productAttributes = ProductAttribute::where('product_id', $id)->get();
        $getCategories = Category::getCategories();
        $getLocations = Location::all();

        return view('admin.products.form', compact(
            'title',
            'product',
            'getCategories',
            'getLocations',
            'productSlide',
            'productAttributes'
        ));
    }

    public function deleteProductImage($id)
    {
        $product = Product::findOrFail($id);
        $path = 'storage/app/public/uploads/products/' . $product->product_image;
        if (File::exists($path)) File::delete($path);
        $product->product_image = null;
        $product->save();
        return back()->with('success','Cover berhasil dihapus');
    }

    public function deleteSlideImage($id)
    {
        $slide = ProductsImage::findOrFail($id);
        $path = 'storage/app/public/uploads/products/slide/' . $slide->image;
        if (File::exists($path)) File::delete($path);
        $slide->delete();
        return back()->with('success','Slide berhasil dihapus');
    }
}
