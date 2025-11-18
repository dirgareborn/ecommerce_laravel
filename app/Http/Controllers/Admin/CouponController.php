<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminRole;
use Intervention\Image\Laravel\Facades\Image;
use App\Models\Coupon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\User;
class CouponController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function coupons()
    {
		
        $title = "Kupon";
        $coupons = Coupon::get()->toArray();

        //Set Admin/Subadmins Permissions 
        $couponModuleCount = AdminRole::where(['admin_id'=>Auth::guard('admin')->user()->id,'module'=>'coupons'])->count();
        $couponModule = [];
        if(Auth::guard('admin')->user()->type=="admin"){
            $couponModule['view_access']=1;
            $couponModule['edit_access']=1;
            $couponModule['full_access']=1;
        }else if($couponModuleCount == 0){
            $message = "This Feature is retriced for you!";
            return redirect('admin/dashboard')->with('error_message',$message);
        }else{
            $couponModule = AdminRole::where(['admin_id'=>Auth::guard('admin')->user()->id,'module'=>'coupons'])->first()->toArray();
        }        
        return view('admin.coupons.coupons')->with(compact('title','coupons','couponModule'));
    }

    public function edit(Request $request, $id=null)
    {
        
        if($id==""){
			$selProds = array();
			$selUsers = array();
            $title = "Tambah Kupon";
            $coupon = new Coupon;
            $message = "Kupon berhasil ditambahkan";
        }else{
            $title = "Ubah Kupon";
            $coupon = Coupon::find($id);
			$selProds = explode(",", $coupon['products']);
			$selUsers = explode(",", $coupon['users']);
            $message = "Kupon berhasil diperbaharui";
        }
		//dd($selProds);
        if($request->isMethod('post')){
            $data = $request->all();
			//echo "<pre>"; print_r($data); die;
            if($id==""){
                $couponCount = Coupon::where('coupon_code', $data['coupon_code'])->count();
                if($couponCount>0){
                    return redirect()->back()->with('error_message','Kupon '. $data['coupon_code'] . ' sudah ada !');
                }
            }
            if($id==""){
                $rules = [
                   // 'coupon_code' => 'required',
                    'expired_date' => 'required',
                ];
            }else{
                $rules = [
                    //'coupon_code' => 'required',
                ];
            }
            $customMessages = [
                'coupon_code.required' => "Kode Kupon harus diisi",
                'expired_date.required' => "Tanggal Expired Harus diisi",
            ];

            $this->validate($request,$rules,$customMessages);
				
				if($data['coupon_option']=="automatic"){
					$coupon_code = Str::random(8);
				}else{
					$coupon_code = $data['coupon_code'];
				}
				if(isset($data['products'])){
					$products = implode(',',$data['products']);
				}else{
					$products = "";
				}
				if(isset($data['users'])){
					$users = implode(',',$data['users']);
				}else{
					$users = "";
				}
				//dd($coupon_code);
                $coupon->coupon_name = $data['coupon_name'];
				$coupon->coupon_option = $data['coupon_option'];
                $coupon->coupon_code = $coupon_code;
                $coupon->coupon_type = $data['coupon_type'];
                $coupon->products = $products;
                $coupon->users = $users;
                $coupon->amount_type = $data['amount_type'];
				$coupon->amount = $data['amount'];
				$coupon->expired_date = $data['expired_date'];
                $coupon->status = $data['status'];
                $coupon->save();

            return redirect('admin/coupons')->with('success_message',$message);
        }
		$products = Product::getProductActive();
		$users = User::select('email','id')->get()->toArray();
        return view('admin.coupons.add_edit_coupon')->with(compact('title','coupon','users','products','selProds','selUsers'));
    }
      /**
     * Update the specified resource in storage.
     */
    public function updateStatus(Request $request)
    {
        if($request->ajax()){
            $data = $request->all();
           // echo "<pre>"; print_r($data); die;
            if($data['status']=="Active"){
                $status = 0;
            }else{
                $status = 1;
            }
            Coupon::where('id',$data['coupon_id'])->update(['status'=>$status]);
            return response()->json(['status'=>$status,'coupon_id'=>$data['coupon_id']]);
        }
    }

    public function deleteCoupon($id)
    {
        Coupon::where('id',$id)->delete();
        return redirect()->back()->with('success_message','Kupon Subsidi Berhasil dihapus');
    }
}
