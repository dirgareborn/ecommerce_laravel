<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccountBank;
use Intervention\Image\Laravel\Facades\Image;
use App\Models\AdminRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
class AccountBankController extends Controller
{
    public function accountbanks(){
        Session::put('page','accountbanks');
        $accountBanks = AccountBank::all()->toArray();

        //Set Admin/Subadmins Permissions 
        $accountbanksModuleCount = AdminRole::where(['admin_id'=>Auth::guard('admin')->user()->id,'module'=>'accountbanksModule'])->count();
        $accountbanksModule = [];
        if(Auth::guard('admin')->user()->type=="admin"){
            $accountbanksModule['view_access']=1;
            $accountbanksModule['edit_access']=1;
            $accountbanksModule['full_access']=1;
        }else if($accountbanksModuleCount == 0){
            $message = "This Featu is retriced for you!";
            return redirect('admin/dashboard')->with('error_message',$message);
        }else{
            $accountbanksModule = AdminRole::where(['admin_id'=>Auth::guard('admin')->user()->id,'module'=>'accountbanksModule'])->first()->toArray();
        }
        return view('admin.banks.banks', compact('accountBanks','accountbanksModule'));
    }
	
	
    /**
     * Show the form for add and editing the specified resource.
     */
    public function edit(Request $request, $id=null)
    {
        // dd($id);
        if($id==""){
            $title = "Tambah Akun Bank";
            $accountbank = new AccountBank;
            $message = "Akun berhasil ditambahkan";
        }else{
            $title = "Ubah Akun Bank";
            $accountbank = AccountBank::find($id);
            $message = "Akun Bank berhasil diperbaharui";
        }
        if($request->isMethod('post')){
            $data = $request->all();

            if($id==""){
                $accountbankCount = AccountBank::where('account_number', $data['account_number'])->count();
                if($accountbankCount>0){
                    return redirect()->back()->with('error_message','Nomor Rekening '. $data['account_number'] . ' sudah ada !');
                }
            }
            if($id==""){
                $rules = [
                    'account_name' => 'required',
					'account_number' => 'required',
                ];
            }else{
                $rules = [
                    'account_name' => 'required',
                    'account_number' => 'required',
                ];
            }
            $customMessages = [
                'account_name.required' => "Nama Rekening harus diisi",
				'account_number.required' => "Nomor Rekening harus diisi",
            ];

            $this->validate($request,$rules,$customMessages);

            if($request->hasFile('bank_icon')){
                $file = $request->file('bank_icon');
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $fileName = str::random(5)."-".date('his')."-".str::random(3).".".$extension;
                        
                $destinationPath = 'front/images/banks'.'/';
                $file->move($destinationPath, $fileName);
                $data['bank_icon'] = $fileName;
				
                }else if (!empty($data['current_image'])){
                    $data['bank_icon'] = $data['current_image'];
                }else{
                    $data['bank_icon'] = "";
                }
                $accountbank->bank_name = $data['bank_name'];
                $accountbank->bank_icon = $data['bank_icon'];
				$accountbank->account_name = $data['account_name'];
				$accountbank->account_number = $data['account_number'];
                $accountbank->status = 1;
                $accountbank->save();

            return redirect('admin/account-banks')->with('success_message',$message);
        }
    
        return view('admin.banks.add_edit_bank')->with(compact('title','accountbank'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateStatus(Request $request, AccountBank $accountbank)
    {
        if($request->ajax()){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if($data['status']=="Active"){
                $status = 0;
            }else{
                $status = 1;
            }
            AccountBank::where('id',$data['bank_id'])->update(['status'=>$status]);
            return response()->json(['status'=>$status,'bank_id'=>$data['bank_id']]);
        }
    }

    public function deleteAccountBank($id)
    {
		$bankImage = AccountBank::select('bank_icon')->where('id',$id)->first();
        $bank_image_path = 'front/images/banks/';
        if(file_exists($bank_image_path.$bankImage->bank_icon)){
            unlink($bank_image_path.$bankImage->bank_icon);
        }
        AccountBank::where('id',$id)->delete();
        return redirect()->back()->with('success_message','Akun Bank Berhasil dihapus');
    }
    public function deleteAccountBankImage($id)
    {
        $bankImage = AccountBank::select('bank_icon')->where('id',$id)->first();
        $bank_image_path = 'front/images/banks/';
        if(file_exists($bank_image_path.$bankImage->bank_icon)){
            unlink($bank_image_path.$bankImage->bank_icon);
        }
        AccountBank::where('id',$id)->update(['bank_icon'=>'']);
        return redirect()->back()->with('success_message','Ikon Bank  Berhasil dihapus');
    }
	
	
}
