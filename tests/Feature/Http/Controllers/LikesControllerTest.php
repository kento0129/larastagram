<?php

namespace Tests\Feature\Http\Controllers;

use Auth;
use App\Like;
use App\Post;
use App\User;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LikesControllerTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * 未認証時はログイン画面にリダイレクト
     *
     * @test
     */
    public function unauthenticatedWhenLoginScreenRedirect()
    {
        $post = factory(Post::class)->create();
        
        // ファイルが登録されているかチェック
        Storage::disk('public')->assertExists('post_images/'.$post->post_photo);
        
        $this->assertFalse(Auth::check());
        $url = route('likes.posts',$post->id);
        $this->get($url)
             ->assertRedirect('/login');
             
        // テスト終了後ファイル削除
        Storage::disk('public')->delete('post_images/'.$post->post_photo);
    }
    
    /**
     * いいね処理に成功
     *
     * @test
     */
    public function successfulToLikeProcess()
    {
        $post = factory(Post::class)->create();
        
        $this->assertFalse(Auth::check());
        $this->actingAs($post->user)
             ->assertTrue(Auth::check());

        // ファイルが登録されているかチェック
        Storage::disk('public')->assertExists('post_images/'.$post->post_photo);
        
        $url = route('likes.posts',$post->id);
        $this->get($url)
             ->assertRedirect('/');

        // データベースに値が登録されているかチェック
        $this->assertDatabaseHas('likes', [
            'post_id' => $post->id,
            'user_id' => $post->user_id,
        ]);
        
        // テスト終了後ファイル削除
        Storage::disk('public')->delete('post_images/'.$post->post_photo);
    }
    
    /**
     * いいね処理に失敗
     *
     * @test
     */
    public function failedToLikeProcess()
    {
        $post = factory(Post::class)->create();
        
        $this->assertFalse(Auth::check());
        $this->actingAs($post->user)
             ->assertTrue(Auth::check());
        
        // ファイルが登録されているかチェック
        Storage::disk('public')->assertExists('post_images/'.$post->post_photo);
        
        //$post_idを空にしてgetリクエストで送信する。
        $post_id = $post->id;
        $post_id = '';

        $url = route('likes.posts',$post_id);
        $this->get($url)
             ->assertStatus(404);

        // データベースに値が登録されていないかチェック
        $this->assertDatabaseMissing('likes', [
            'post_id' => $post->id,
            'user_id' => $post->user_id,
        ]);

        // テスト終了後ファイル削除
        Storage::disk('public')->delete('post_images/'.$post->post_photo);
    }
    
    /**
     * いいね取り消し処理に成功
     *
     * @test
     */
    public function successfulToLikeCancelProcess()
    {
        $like = factory(Like::class)->create();
        $this->actingAs($like->post->user);
        $this->assertTrue(Auth::check());
        
        // ファイルが登録されているかチェック
        Storage::disk('public')->assertExists('post_images/'.$like->post->post_photo);
        
        $url = route('likes.delete',$like->id);
        $this->get($url)
             ->assertRedirect('/');

        // データベースに値が登録されていないかチェック
        $this->assertDatabaseMissing('likes', [
            'id'      => $like->id,
            'post_id' => $like->post_id,
            'user_id' => $like->user_id,
        ]);
        
        // テスト終了後ファイル削除
        Storage::disk('public')->delete('post_images/'.$like->post->post_photo);
    }
    
    /**
     * いいね取り消し処理に失敗
     *
     * @test
     */
    public function failedToLikeCancelProcess()
    {
        $like = factory(Like::class)->create();
        $this->actingAs($like->post->user);
        $this->assertTrue(Auth::check());

        // ファイルが登録されているかチェック
        Storage::disk('public')->assertExists('post_images/'.$like->post->post_photo);

        //$like_idを空にしてgetリクエストで送信する。
        $like_id = $like->id;
        $like_id = '';
  
        $url = route('likes.delete',$like_id);
        $this->get($url)
             ->assertStatus(404);

        // データベースに値が登録されているかチェック
        $this->assertDatabaseHas('likes', [
            'id'      => $like->id,
            'post_id' => $like->post_id,
            'user_id' => $like->user_id,
        ]);
        
        // テスト終了後ファイル削除
        Storage::disk('public')->delete('post_images/'.$like->post->post_photo);
    }
}
