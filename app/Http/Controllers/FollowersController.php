<?php

namespace App\Http\Controllers;

use Auth;
use App\Follower;
use App\User;
use Illuminate\Http\Request;

class FollowersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function store($followed_id)
    {
        // Followerモデル作成
        $follower = new Follower;
        $follower->following_id = Auth::user()->id;
        $follower->followed_id = $followed_id;
        $follower->save();

        // 「/」 ルートにリダイレクト
        return redirect('/');
    }
    
    public function destroy($followed_id)
    {
        $follower = Follower::where('followed_id',$followed_id)
                              ->firstOrFail();

        $follower->where('following_id',Auth::user()->id)
                 ->where('followed_id',$followed_id)
                 ->delete();
        
        // 「/」 ルートにリダイレクト
        return redirect('/');
    }
}
