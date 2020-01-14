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
        $user = factory(User::class)->create([
            'password'  => bcrypt('secret')
        ]);
        $file = UploadedFile::fake()->image(date('YmdHis'). '_' .'public.jpg');
        
        Storage::disk('public')->putFileAs('post_images', $file, $file->getClientOriginalName(), 'public');
        
        $post = factory(Post::class)->create([
            'user_id'  => $user->id,
            'post_photo' => $file->getClientOriginalName(),
        ]);
        
        // ファイルが登録されているかチェック
        Storage::disk('public')->assertExists('post_images/'.$post->post_photo);
        
        $this->assertFalse(Auth::check());
        $url = route('likes.posts',$post->id);
        $this->get($url)
             ->assertRedirect('/login');
    }
    
    /**
     * いいね処理に成功
     *
     * @test
     */
    public function successfulToLikeProcess()
    {
        $user = factory(User::class)->create([
            'password'  => bcrypt('secret')
        ]);
        $this->assertFalse(Auth::check());
        $this->actingAs($user)
             ->assertTrue(Auth::check());
        
        $file = UploadedFile::fake()->image(date('YmdHis'). '_' .'public.jpg');
        Storage::disk('public')->putFileAs('post_images', $file, $file->getClientOriginalName(), 'public');
        
        $post = factory(Post::class)->create([
            'user_id'  => $user->id,
            'post_photo' => $file->getClientOriginalName(),
        ]);
        
        // ファイルが登録されているかチェック
        Storage::disk('public')->assertExists('post_images/'.$post->post_photo);
        
        $url = route('likes.posts',$post->id);
        $this->get($url)
             ->assertRedirect('/');

        // データベースに値が登録されているかチェック
        $this->assertDatabaseHas('likes', [
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);
    }
    
    /**
     * いいね処理に失敗
     *
     * @test
     */
    public function failedToLikeProcess()
    {
        $user = factory(User::class)->create([
            'password'  => bcrypt('secret')
        ]);
        $this->assertFalse(Auth::check());
        $this->actingAs($user)
             ->assertTrue(Auth::check());
        
        $file = UploadedFile::fake()->image(date('YmdHis'). '_' .'public.jpg');
        Storage::disk('public')->putFileAs('post_images', $file, $file->getClientOriginalName(), 'public');
        
        $post = factory(Post::class)->create([
            'user_id'  => $user->id,
            'post_photo' => $file->getClientOriginalName(),
        ]);
        
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
            'user_id' => $user->id,
        ]);
    }
    
    /**
     * いいね取り消し処理に成功
     *
     * @test
     */
    public function successfulToLikeCancelProcess()
    {
       $user = factory(User::class)->create([
            'password'  => bcrypt('secret')
        ]);
        $this->assertFalse(Auth::check());
        $this->actingAs($user)
             ->assertTrue(Auth::check());
        
        $file = UploadedFile::fake()->image(date('YmdHis'). '_' .'public.jpg');
        Storage::disk('public')->putFileAs('post_images', $file, $file->getClientOriginalName(), 'public');
        
        $post = factory(Post::class)->create([
            'user_id'  => $user->id,
            'post_photo' => $file->getClientOriginalName(),
        ]);
        
        // ファイルが登録されているかチェック
        Storage::disk('public')->assertExists('post_images/'.$post->post_photo);
        
        $like = factory(Like::class)->create([
            'post_id'  => $post->id,
            'user_id' => $user->id,
        ]);
        
        $url = route('likes.delete',$like->id);
        $this->get($url)
             ->assertRedirect('/');

        // データベースに値が登録されていないかチェック
        $this->assertDatabaseMissing('likes', [
            'id'      => $like->id,
            'post_id' => $like->post_id,
            'user_id' => $like->user_id,
        ]);
    }
    
    /**
     * いいね取り消し処理に失敗
     *
     * @test
     */
    public function failedToLikeCancelProcess()
    {
       $user = factory(User::class)->create([
            'password'  => bcrypt('secret')
        ]);
        $this->assertFalse(Auth::check());
        $this->actingAs($user)
             ->assertTrue(Auth::check());
        
        $file = UploadedFile::fake()->image(date('YmdHis'). '_' .'public.jpg');
        Storage::disk('public')->putFileAs('post_images', $file, $file->getClientOriginalName(), 'public');
        
        $post = factory(Post::class)->create([
            'user_id'  => $user->id,
            'post_photo' => $file->getClientOriginalName(),
        ]);
        
        // ファイルが登録されているかチェック
        Storage::disk('public')->assertExists('post_images/'.$post->post_photo);
        
        $like = factory(Like::class)->create([
            'post_id'  => $post->id,
            'user_id' => $user->id,
        ]);
        
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
    }
}
