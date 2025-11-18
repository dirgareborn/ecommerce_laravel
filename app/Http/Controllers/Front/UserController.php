<?php

namespace App\Http\Controllers\Front;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Testimonial;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $MenuCategories;

    public function __construct(Controller $MenuCategories)
    {
        $this->middleware('auth');
        $this->MenuCategories;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('cart');
    }

    public function orders()
    {
        $orders = Order::with('orders_products')->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
        return view('front.customers.order_list')->with(compact('orders'));
    }

    public function account()
    {
        return view('front.customers.account');
    }

    public function profil()
    {
        return view('front.customers.profil');
    }

    public function updatePassword(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            // cek password lama benar
            if(Hash::check($data['current_pwd'],Auth::guard('web')->user()->password)){
                // cek password baru dan konfirmasi password cocok
                if($data['new_pwd']==$data['confirm_pwd']){
                    // Update password baru
                    User::where('id', Auth::guard('web')->user()->id)->update(['password'=> bcrypt($data['new_pwd'])]);
                    return redirect()->back()->with('success_message','Password anda berhasil diperbarui!');
                }else{
                    return redirect()->back()->with('error_message','Password yang anda masukkan tidak cocok !');
                }
            }else{
                return redirect()->back()->with('error_message','Password yang anda masukkan salah !');
            }
        }
        return view('front.customers.account');
    }

    public function checkCurrentPassword(Request $request){
        $data = $request->all();
        if(Hash::check($data['current_pwd'],Auth::guard('web')->user()->password)){
            return "true";
        }else{
            return "false";
        }
    }

    public function updateDetail(Request $request){

        if($request->isMethod('post')){
            $data = $request->all();

            $rules = [
                'name'    => 'required|max:35',
            ];

            $customMessages = [
                'name.required'   => 'Nama harus terisi',
                // 'name.regex'   => 'Nama harus valid',
                'name.max'        =>  'Nama tidak boleh lebih dari 35 karakter',

            ];

            $this->validate($request,$rules,$customMessages);
			//echo "<pre>"; print_r($customMessages); die;
            // update detail admin
			//dd($data);
            if($request->hasFile('image')){
                $file = $request->file('image');
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $fileName = $filename ."-".date('his')."-".str::random(3).".".$extension;

                $destinationPath = 'front/images/customers'.'/';
                $file->move($destinationPath, $fileName);
                $data['image'] = $fileName;
            }else if (!empty($data['current_image'])){
                $data['image'] = $data['current_image'];
            }else{
                $data['image'] = "";
            }
            $user = User::where('id', Auth::user()->id)->update([
                'name'=> $data['name'],
                'mobile'=> $data['mobile'],
                'address'=> $data['address'],
                'pincode'=> $data['pincode'],
                'image'=> $data['image']
            ]);

            if($user){
               return redirect()->back()->with('success_message','Data detail anda berhasil diperbarui!');
           }else{
               return redirect()->back()->with('error_message','Data detail gagal diperbarui!');
           }
       }
       // return view('front.customers.profil');
   }

   public function testimonial(Request $request)
   {
    $user = Auth::user();

        // Ambil testimonial terbaru
    $getTestimonial = Testimonial::where('user_id', $user->id)
    ->latest()
    ->first();

        // Jika submit form
    if ($request->isMethod('post')) {
        $validated = $request->validate([
            'description' => 'required|string|max:1000',
        ], [
            'description.required' => 'Testimonial harus diisi.',
            'description.max' => 'Testimonial terlalu panjang (maksimum 1000 karakter).',
        ]);

            // Simpan testimonial baru
        Testimonial::create([
            'user_id' => $user->id,
            'description' => trim($validated['description']),
                'status' => 0, // pending review
            ]);

        return redirect()->back()->with('success_message', 'Testimonial Anda berhasil dikirim dan menunggu konfirmasi admin.');
    }

        // Ambil semua testimonial user (riwayat)
    $testimonials = Testimonial::where('user_id', $user->id)
    ->latest()
    ->get();

    return view('front.customers.testimonial', compact('getTestimonial', 'testimonials'));
}


}
