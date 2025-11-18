<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Information;
use App\Models\InformationComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InformationController extends Controller
{
    public function index()
    {
        $informations = Information::where('status', 'published')->latest('published_at')->paginate(9);
        $page_title = 'Seputar Informasi Bermanfaat Untuk Anda';
        return view('front.informations.index', compact('informations','page_title'));
    }

    public function show(Information $information,$slug)
{
    $information = Information::where('slug', $slug)
                        ->with(['comments.replies.user', 'comments.user'])
                        ->withCount('comments') 
                        ->firstOrFail();

    $information->load(['comments' => fn($q) => $q->where('status','approved')->whereNull('parent_id')->with(['replies'=>fn($q2)=>$q2->where('status','approved')])]);
    // dd($information);
    $page_title = $information->title;
    return view('front.informations.show', compact('information','page_title'));
}

public function comment(Request $request, Information $information)
{
    $request->validate(['comment'=>'required|string']);
    $comment = $information->comments()->create([
        'user_id' => auth()->id(),
        'comment' => $request->comment,
        'status' => 'pending'
    ]);
    return response()->json(['success'=>true,'comment'=>$comment]);
}

public function reply(Request $request, Information $information)
{
    $request->validate(['comment'=>'required|string','parent_id'=>'required|exists:information_comments,id']);
    $reply = $information->comments()->create([
        'user_id' => auth()->id(),
        'parent_id' => $request->parent_id,
        'comment' => $request->comment,
        'status' => 'pending'
    ]);
    return response()->json(['success'=>true,'reply'=>$reply]);
}

}
