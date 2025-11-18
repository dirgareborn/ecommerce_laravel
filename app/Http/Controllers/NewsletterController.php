<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewPostNotification;

class NewsletterController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['email' => 'required|email|unique:newsletter_subscribers,email']);

        try {
            NewsletterSubscriber::create(['email' => $request->email]);
            return response()->json(['success'=>true, 'message'=>'Berhasil berlangganan newsletter!']);
        } catch (\Exception $e) {
            return response()->json(['success'=>false, 'message'=>'Gagal mendaftar.']);
        }
    }

    // Contoh kirim email otomatis ke semua subscriber ketika ada postingan baru
    public function notifyNewPost($post)
    {
        $subscribers = NewsletterSubscriber::all();

        foreach($subscribers as $subscriber) {
            Mail::to($subscriber->email)->send(new NewPostNotification($post));
        }
    }
}
