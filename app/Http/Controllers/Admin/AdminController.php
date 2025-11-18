<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Session;
use App\Models\AdminsRole;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    
    
    public function login(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();

            $rules = [
                'email' => 'required|email|max:255',
                'password' => 'required|max:30'
            ];

            $customMessages = [
                'email.required' => "Email is required",
                'email.email' => 'Valid Email is required',
                'password.required' => 'Password is required',
            ];

            $this->validate($request,$rules,$customMessages);
            if(Auth::guard('admin')->attempt([
                'email'=>$data['email'],
                'password'=>$data['password']
                ])){
                    // 
                    $request->session()->regenerate();
                     // Simpan session id di tabel users
                    $user = Auth::guard('admin')->user();
                    $user->session_id = session()->getId();
                    $user->save();
                    // Remember Me Email dan Password
                    if(isset($data['remember'])&&!empty($data['remember'])){
                        setcookie("email",$data['email'],time()+3600);
                        setcookie("password",$data['password'],time()+3600);
                    }else{
                        setcookie("email","");
                        setcookie("password","");
                    }

                    return redirect()->route('admin.dashboard');
                }else{
                    return redirect()->back()->with("error_message","Invalid Email or Password!");
                }
        }
        return view('admin.login');
    }

    public function logout(Request $request){
        $user = Auth::guard('admin')->user();
        if ($user) {
            $user->session_id = null;
            $user->save();
        }

        Auth::guard('admin')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('beranda');
    }

    public function updatePassword(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            // cek password lama benar
            if(Hash::check($data['current_pwd'],Auth::guard('admin')->user()->password)){
                // cek password baru dan konfirmasi password cocok
                if($data['new_pwd']==$data['confirm_pwd']){
                    // Update password baru
                    Admin::where('id', Auth::guard('admin')->user()->id)->update(['password'=> bcrypt($data['new_pwd'])]);
                    return redirect()->back()->with('success_message','Password anda berhasil diperbarui!');
                }else{
                    return redirect()->back()->with('error_message','Password yang anda masukkan tidak cocok !');
                }
            }else{
                return redirect()->back()->with('error_message','Password yang anda masukkan salah !');
            }
        }
        return view('admin.updatePassword');
    }

    public function checkCurrentPassword(Request $request){
        $data = $request->all();
        if(Hash::check($data['current_pwd'],Auth::guard('admin')->user()->password)){
            return "true";
        }else{
            return "false";
        }
    }

    public function updateDetail(Request $request){
        // dd($request);
        if($request->isMethod('post')){
            $data = $request->all();

            $rules = [
                'admin_name'    => 'required|regex:/^[\pL\s\-]+$/u|max:25',
                'admin_mobile'  => 'required|numeric|digits:12',
                'admin_image'  => 'image'
            ];

            $customMessages = [
                'admin_name.required'   => 'Nama harus terisi',
                'admin_name.regex'      => 'Nama yang anda masukkan harus Valid',
                'admin_name.max'        =>  'Nama tidak boleh lebih dari 25 karakter',
                'admin_mobile.required' => 'Nomor Handphone harus terisi',
                'admin_mobile.numeric'  => 'Nomor Handphone valid',
                'admin_mobile.digits'   =>  'Nomor Handphone Harus 12 Digit',
                'admin_image.image'     =>  'Foto Harus Terisi',
            ];

            $this->validate($request,$rules,$customMessages);
            // update detail admin
            if($request->hasFile('admin_image')){
                $file = $request->file('admin_image');
                $extension = $file->getClientOriginalExtension();
                $fileName = Str::random(10)."-".date('his').".".$extension;
                        
                $destinationPath = 'admin/images/avatars'.'/';
                $file->move($destinationPath, $fileName);
                $data['admin_image'] = $fileName;
                }else if (!empty($data['current_image'])){
                    $data['admin_image'] = $data['current_image'];
                }else{
                    $data['admin_image'] = "";
                }
            Admin::where('id', Auth::guard('admin')->user()->id)->update([
                'name'=> $data['admin_name'],
                'mobile'=> $data['admin_mobile'],
                'image'=> $data['admin_image']
        ]);
            return redirect()->back()->with('success_message','Data detail anda berhasil diperbarui!');
        }
        return view('admin.updateDetail');
    }

    public function subadmins(){
        Session::put('page','subadmins');
        $subadmins = Admin::whereNotIn('type',['admin'])->get();
        // dd($subadmins);
        return view('admin.subadmins.subadmins', compact('subadmins'));
    }
    public function edit(Request $request, $id=null){
        if($id==""){
            $title = "Tambah Data Admin";
            $subadmin = new Admin;
            $message = "Admin berhasil ditambahkan";
        }else{
            $title = "Ubah Data Admin";
            $subadmin = Admin::find($id);
            $message = "Data Admin berhasil diperbaharui";
        }

        if($request->isMethod('post')){
            $data = $request->all();

            if($id==""){
                $subadminCount = Admin::where('email', $data['admin_email'])->count();
                if($subadminCount>0){
                    return redirect()->back()->with('error_message','Data admin sudah ada !');
                }
            }

            $rules = [
                'admin_name'    => 'required|regex:/^[\pL\s\-]+$/u|max:25',
                'admin_mobile'  => 'required|numeric|digits:12',
                'admin_type'    => 'required',
                'admin_email'    => 'required|email',
                'admin_password'    => 'required',
            ];

            $customMessages = [
                'admin_name.required'   => 'Nama harus terisi',
                'admin_name.regex'      => 'Nama yang anda masukkan harus Valid',
                'admin_name.max'        =>  'Nama tidak boleh lebih dari 25 karakter',
                'admin_type.required'   =>  'Tipe Admin tidak bolenh kosong',
                'admin_mobile.required' => 'Nomor Handphone harus terisi',
                'admin_mobile.numeric'  => 'Nomor Handphone valid',
                'admin_mobile.digits'   =>  'Nomor Handphone Harus 12 Digit',
                'admin_email.email'     =>  'Nama Email Harus Valid',
                'admin_email.required'  =>  'Email Harus diisi',
                'admin_password.required'     =>  'Password diisi',

            ];
            
            $this->validate($request,$rules,$customMessages);
            if($request->hasFile('admin_image')){
                $avatar = $request->file('admin_image');
                $filename = time() . '.' . $avatar->getClientOriginalExtension();
                $image = Image::read($avatar);
                // Resize image
                $image->resize(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                })->save(public_path('admin/images/avatars/' . $filename));
                $data['admin_image'] = $filename;
                }else if (!empty($data['current_image'])){
                    $data['admin_image'] = $data['current_image'];
                }else{
                    $data['admin_image'] = "";
                }
                $subadmin->image = $data['admin_image'];
                $subadmin->name = $data['admin_name'];
                $subadmin->mobile = $data['admin_mobile'];
                $subadmin->status = 1;
                if($id==""){
                $subadmin->email = $data['admin_email'];
                $subadmin->type = 'subadmin';
                }
                if($data['admin_password']!=""){
                    $subadmin->password = bcrypt($data['admin_password']);
                }
                $subadmin->save();
            return redirect('admin/subadmins')->with('success_message',$message);
        }
        return view('admin.subadmins.add_subadmin', compact('subadmin','title'));
    }

    public function updateSubadminStatus(Request $request){
        
        if($request->ajax()){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if($data['status']=="Active"){
                $status = 0;
            }else{
                $status = 1;
            }
            Admin::where('id', $data['subadmin_id'])->update(['status'=>$status]);
            return response()->json(['status'=>$status,'subadmin_id'=>$data['subadmin_id']]);
        }

        

    }
    public function deleteSubAdmin($id)
    {
        Admin::where('id',$id)->delete();
        return redirect()->back()->with('success_message','Data Admin Berhasil dihapus');
    }

    public function updateRole($id,Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
        AdminsRole::where('admin_id',$id)->delete();
        $permissions = ['view','edit','full'];
        
        foreach (['categories', 'cms_pages','products'] as $module) {
            $role = new AdminsRole;
            $role->admin_id = $id;
            $role->module = $module;
            foreach ($permissions as $permission) {
                $storedData = "{$module}_$permission";
                if (isset($data[$module][$permission])) {
                    $storedData = $data[$module][$permission];
                } else {
                    $storedData = 0;
                }
                $role->{$permission . '_access'} = $storedData;
            }
            $role->save();
        }


            $message = "Role Berhasil di update";
            return redirect()->back()->with('success_message',$message);

        }

        $adminRoles = AdminsRole::where('admin_id', $id)->get()->toArray();
        $adminDetails = Admin::where('id', $id)->first()->toArray();
        $title = "Update ".$adminDetails['name']."  Role / Permission";

        return view('admin.subadmins.update_roles')->with(compact('title','id','adminRoles'));
    }
}
