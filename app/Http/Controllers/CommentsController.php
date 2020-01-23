<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Post;
use Auth;
use Validator;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function store(Request $request)
    {
        $comment = new Comment;
        $comment->comment = $request->comment;
        $comment->post_id = $request->post_id;
        $comment->user_id = Auth::user()->id;
        $comment->save();

        return back();
    }
    
    public function destroy(Request $request)
    {
        $comment = Comment::findOrFail($request->comment_id);
        $comment->delete();
        
        return back();
    }
}
