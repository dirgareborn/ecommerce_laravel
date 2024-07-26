<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\ProfilWebsite;
use App\Models\Product;

class IndexController extends Controller
{
    public function index(){
        $homeSliderBanners = Banner::where('type','Slider')->where('status',1)->orderBy('sort','ASC')->get()->toArray();
        $products = Product::with('images','locations','categories')->where('status',1)->orderBy('id','Desc')->limit(12)->get()->toArray();
        // dd($products);
        return view('front.index')->with(compact('homeSliderBanners','products'));
    }

    public function visiMisi(){
        $visimisi = ProfilWebsite::first();
        $page_title       = 'Hubungi Kami';
        return view('front.visi-misi', compact('visimisi','page_title'));
    }
    public function strukturOrganisasi(){
        $strukturOrganisasi = ProfilWebsite::first();
        $page_title       = 'Struktur Organisasi';
        return view('front.struktur-organisasi', compact('strukturOrganisasi','page_title'));

    }public function kontak(){
        $kontak = ProfilWebsite::first();
        $page_title       = 'Kontak Kami';
        return view('front.contact', compact('kontak','page_title'));
    }
    
}