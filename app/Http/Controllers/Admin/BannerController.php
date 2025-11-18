<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminRole;
use Intervention\Image\Laravel\Facades\Image;
use App\Models\Banner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function banners()
    {
        $title = "Banner";
        $banners = Banner::get()->toArray();

        //Set Admin/Subadmins Permissions 
        $bannerModuleCount = AdminRole::where(['admin_id'=>Auth::guard('admin')->user()->id,'module'=>'banners'])->count();
        $bannerModule = [];
        if(Auth::guard('admin')->user()->type=="admin"){
            $bannerModule['view_access']=1;
            $bannerModule['edit_access']=1;
            $bannerModule['full_access']=1;
        }else if($bannerModuleCount == 0){
            $message = "This Featu is retriced for you!";
            return redirect('admin/dashboard')->with('error_message',$message);
        }else{
            $bannerModule = AdminRole::where(['admin_id'=>Auth::guard('admin')->user()->id,'module'=>'banners'])->first()->toArray();
        }        
        return view('admin.banners.banners')->with(compact('title','banners','bannerModule'));
    }

    public function edit(Request $request, $id=null)
    {
        // dd($id);
        if($id==""){
            $title = "Tambah Banner";
            $banner = new Banner;
            $message = "Banner berhasil ditambahkan";
        }else{
            $title = "Ubah Banner";
            $banner = Banner::find($id);
            $message = "Banner berhasil diperbaharui";
        }
        if($request->isMethod('post')){
            $data = $request->all();

            if($id==""){
                $bannerCount = Banner::where('title', $data['title'])->count();
                if($bannerCount>0){
                    return redirect()->back()->with('error_message','Banner '. $data['title'] . ' sudah ada !');
                }
            }
            if($id==""){
                $rules = [
                    'title' => 'required',
                    'banner_image' => 'required',
                ];
            }else{
                $rules = [
                    'title' => 'required',
                ];
            }
            $customMessages = [
                'title.required' => "Judul Banner harus diisi",
                'banner_image.required' => "Gambar Harus diisi",
            ];

            $this->validate($request,$rules,$customMessages);

            if($request->hasFile('banner_image')){
                $file = $request->file('banner_image');
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $fileName = $filename."-".date('his')."-".str::random(3).".".$extension;
                        
                $destinationPath = 'front/images/banners'.'/';
                $file->move($destinationPath, $fileName);
                $data['image'] = $fileName;
                }else if (!empty($data['current_image'])){
                    $data['image'] = $data['current_image'];
                }else{
                    $data['image'] = "";
                }
                $banner->title = $data['title'];
                $banner->image = $data['image'];
                $banner->type = $data['type'];
                $banner->link = $data['link'];
                $banner->alt = $data['alt'] ?? 0;
                $banner->sort = $data['sort'];
                $banner->status = 1;
                $banner->save();

            return redirect('admin/banners')->with('success_message',$message);
        }
    
        return view('admin.banners.add_edit_banner')->with(compact('title','banner'));
    }
      /**
     * Update the specified resource in storage.
     */
    public function updateStatus(Request $request, Banner $banner)
    {
        if($request->ajax()){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if($data['status']=="Active"){
                $status = 0;
            }else{
                $status = 1;
            }
            Banner::where('id',$data['banner_id'])->update(['status'=>$status]);
            return response()->json(['status'=>$status,'banner_id'=>$data['banner_id']]);
        }
    }

    public function deleteBanner($id)
    {
        Banner::where('id',$id)->delete();
        return redirect()->back()->with('success_message','Banner Berhasil dihapus');
    }
    public function deleteBannerImage($id)
    {
        $bannerImage = Banner::select('image')->where('id',$id)->first();
        $banner_image_path = 'front/images/banners/';
        if(file_exists($banner_image_path.$bannerImage->image)){
            unlink($banner_image_path.$bannerImage->image);
        }
        Banner::where('id',$id)->update(['image'=>'']);
        return redirect()->back()->with('success_message','Foto Kategori  Berhasil dihapus');
    }
}
