<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CmsPage;
use App\Models\Banner;
use App\Models\Mitra;
use App\Models\ProfilWebsite;
use App\Models\Product;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Route; 
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Information;
class PageController extends Controller
{

    public function getRouteKeyName()
    {
        return 'url';
    }

    public function show(CmsPage $url){
        $url = Route::getFacadeRoot()->current()->uri;
        $pageCount = CmsPage::where(['url'=>$url,'status'=>'Ready'])->count();
        if($pageCount>0){
            $cmspageDetail = CmsPage::cmspageDetails($url);
            // dd($cmspageDetail);
            return view('front.pages.page')->with(['cmspageDetail' => $cmspageDetail]);
        }else{
            abort(404);
        }

    }
	
	    public function index(){
        $homeSliderBanners = Banner::activeSlider()->get()->toArray();
        $products = Product::active()->with(['images','locations','category'])->latest()->get()->toArray();
        $getTestimonial = Testimonial::approved()->with('user')->get()->toArray();
        $informations = Information::where('status', 'published')->latest('published_at')->paginate(9);
        $mitra = Mitra::where('status',1)->latest()->get();
        // dd($mitra);
        $meta_title = 'BPB UNM';
        $meta_description = '';
        return view('front.index')->with(compact('homeSliderBanners','mitra','products','getTestimonial','meta_title','informations'));
    }

		public function visiMisi(){
			$visimisi = ProfilWebsite::first();
			$page_title       = 'Visi dan Misi BPB UNM';
			return view('front.pages.visi-misi', compact('visimisi','page_title'));
		}
		public function strukturOrganisasi(){
			$strukturOrganisasi = ProfilWebsite::first();
			$page_title       = 'Struktur Organisasi';
			return view('front.pages.struktur-organisasi', compact('strukturOrganisasi','page_title'));

		}
		public function kontak(){
			$kontak = ProfilWebsite::first();
			$page_title       = 'Kontak Kami';
			return view('front.pages.contact', compact('kontak','page_title'));
		}
		 public function kirimKontak(Request $request)
		{
		    // ✅ Validasi input
		    $validated = $request->validate([
		        'name'    => 'required|string|max:100',
		        'email'   => 'required|email|max:150',
		        'subject' => 'nullable|string|max:150',
		        'message' => 'required|string|max:2000',
		    ], [
		        'required' => ':attribute wajib diisi.',
		        'email'    => 'Format email tidak valid.',
		        'max'      => ':attribute terlalu panjang.',
		    ]);

		    try {
		        // ✅ Kirim email menggunakan Mail::send agar bisa dikembangkan nanti
		        Mail::raw(
		            "📩 Pesan baru dari formulir kontak:\n\n" .
		            "Nama: {$validated['name']}\n" .
		            "Email: {$validated['email']}\n" .
		            ($validated['subject'] ? "Subjek: {$validated['subject']}\n" : "") .
		            "\nPesan:\n{$validated['message']}",
		            function ($mail) use ($validated) {
		                $mail->to('bpbblu.unm@gmail.com') // email tujuan
		                     ->subject('Pesan Baru dari Form Kontak');
		            }
		        );

		        // ✅ (Opsional) Simpan log pesan ke database atau file log
		        Log::info('Pesan kontak terkirim', $validated);

		        return response()->json([
		            'success' => true,
		            'message' => 'Pesan berhasil dikirim! Terima kasih telah menghubungi kami.'
		        ]);

		    } catch (\Exception $e) {
		        Log::error('Gagal mengirim pesan kontak: ' . $e->getMessage());

		        return response()->json([
		            'success' => false,
		            'message' => 'Terjadi kesalahan saat mengirim pesan. Silakan coba lagi nanti.'
		        ], 500);
		    }
		}
		public function faq(){
			//$kontak = ProfilWebsite::first();
			$page_title       = 'Pertanyaan Umum';
			return view('front.pages.faq', compact('page_title'));
		}public function cookies(){
			//$kontak = ProfilWebsite::first();
			$page_title       = 'kebijakan Privasi';
			return view('front.pages.cookies', compact('page_title'));
		}
}
