<?php

namespace App\Http\Controllers;

use App\Like;
use App\Post;
use Auth;
use Validator;
use Illuminate\Http\Request;

class LikesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function store($post_id)
    {
        // Likeモデル作成
        $like = new Like;
        
        $like->create([
            'post_id' => $post_id,
            'user_id' => Auth::user()->id,
        ]);
        
        $like = $like->where('post_id',$post_id)->where('user_id',Auth::user()->id)->firstOrFail();
        $like_id = $like->id;

        $text = "<div><strong>";
        $i = 0;
        $post = Post::findOrFail($post_id);
        foreach($post->likes as $like) {
            if(count($post->likes) == 1) 
            {
                $text.=$like->user->user_name. "</strong> が「いいね！」しました</div>";
            }
            elseif($i == 1 && count($post->likes) == 2)
            {
                $text.="</strong> , <strong>".$like->user->user_name."</strong> が「いいね！」しました</div>";
            }
            elseif($i != 0)
            {
                $text.="</strong> , <strong>他".(count($post->likes)-1) ."人</strong> が「いいね！」しました</div>";
                break;
            }
            else
            {
                $text.=$like->user->user_name;
            }
            $i++;
        }
        return response()->json(['post_id' => $post_id, 'like_id' => $like_id, 'text' => $text]);
    }
    
    public function destroy($like_id)
    {
        $like = Like::findOrFail($like_id);
        $post_id = $like->post_id;
        $like->delete();
        
        $text = "<div><strong>";
        $i = 0;
        $post = Post::findOrFail($post_id);
        if(count($post->likes) == 0)
        {
            $text.="</strong></div>";
        }
        else
        {
            foreach($post->likes as $like) {
                if(count($post->likes) == 1) 
                {
                    $text.=$like->user->user_name. "</strong> が「いいね！」しました</div>";
                }
                elseif($i == 1 && count($post->likes) == 2)
                {
                    $text.="</strong> , <strong>".$like->user->user_name."</strong> が「いいね！」しました</div>";
                }
                elseif($i != 0)
                {
                    $text.="</strong> , <strong>他".(count($post->likes)-1) ."人</strong> が「いいね！」しました</div>";
                    break;
                }
                else
                {
                    $text.=$like->user->user_name;
                }
                $i++;
            }
        }

        return response()->json(['post_id' => $post_id, 'text' => $text]);
    }
}
