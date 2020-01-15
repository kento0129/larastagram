<?php

namespace App\Http\Controllers;

use App\Post;
use App\Like;
use Auth;
use Validator;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

class PostsController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $posts = Post::limit(10)
                       ->orderBy('created_at', 'desc')
                       ->get();
                       
        return view('post/index', ['posts' => $posts]);
    }
    
    public function new()
    {
        return view('post/new');
    }
    
    public function store(Request $request)
    {
        //バリデーション（入力値チェック）
        $validator = Validator::make($request->all() , [
            'caption' => 'required|max:255', 
            'post_photo' => 'required|mimes:jpeg,png,jpg,gif'
        ]);

        //バリデーションの結果がエラーの場合
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        // Postモデル作成
        $post = new Post;
        $post->caption = $request->caption;
        $post->user_id = Auth::user()->id;
        
        $filename = $post->user_id . '_' . 'post_' . date('YmdHis') . '.' .$request->post_photo->getClientOriginalExtension();
        $image = $request->file('post_photo');

        // テスト環境, ローカル環境用の記述
        if (app()->isLocal() || app()->runningUnitTests())
        {
            Storage::disk('public')->putFileAs('post_images', $image, $filename ,'public');
            $post->post_photo = $filename;
        }
        // 本番環境用の記述
        else
        {
            $path = Storage::disk('s3')->putFileAs('post_images', $image, $filename ,'public');
            $post->post_photo = Storage::disk('s3')->url($path);
        }
        $post->save();
        // 「/」 ルートにリダイレクト
        return redirect('/');
    }
    
    public function destroy($post_id)
    {
        $post = Post::findOrFail($post_id);
        
        // テスト環境, ローカル環境用の記述
        if (app()->isLocal() || app()->runningUnitTests())
        {
            Storage::disk('public')->delete('post_images/'.$post->post_photo);
        }
        // 本番環境用の記述
        else
        {
            $delete_path = strstr($post->post_photo, 'post_images/');
            Storage::disk('s3')->delete($delete_path);
        }
        $post->delete();
        // 「/」 ルートにリダイレクト
        return redirect('/');
    }
}
