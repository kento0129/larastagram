<?php

namespace Tests\Feature\Http\Controllers;

use Auth;
use App\Comment;
use App\Post;
use App\User;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CommentsControllerTest extends TestCase
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
        $url = route('comments.posts',$post->id);
        $this->post($url, [
            'post_id' => $post->id,
            'comment' => 'テキストテキスト',
        ])
             ->assertRedirect('/login');
    }
    
    /**
     * コメント投稿処理が成功
     *
     * @test
     */
    public function commentPostsProcessingSuccessful()
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
        
        $url = route('comments.posts',$post->id);
        $this->post($url, [
            'post_id' => $post->id,
            'comment' => 'テキストテキスト',
        ]);

        // データベースに値が登録されているかチェック
        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'comment' => 'テキストテキスト',
        ]);
    }
    
    /**
     * コメント投稿処理が失敗
     *
     * @test
     */
    public function commentPostsProcessingFailed()
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

        $url = route('comments.posts',$post_id);
        $this->post($url, [
            'post_id' => $post->id,
            'comment' => 'テキストテキスト',
        ])
             ->assertStatus(404);

        // データベースに値が登録されていないかチェック
        $this->assertDatabaseMissing('comments', [
            'post_id' => $post->id,
            'comment' => 'テキストテキスト',
        ]);
    }
    
    /**
     * コメント取消処理が成功
     *
     * @test
     */
    public function commentCancelProcessingSuccessful()
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
        
        $comments = factory(Comment::class)->create([
            'post_id'  => $post->id,
            'user_id' => $user->id,
        ]);
        
        $url = route('comments.delete',$comments->id);
        $this->get($url)
             ->assertRedirect('/');

        // データベースに値が登録されていないかチェック
        $this->assertDatabaseMissing('comments', [
            'id'      => $comments->id,
            'post_id' => $comments->post_id,
            'user_id' => $comments->user_id,
        ]);
    }
    
    /**
     * コメント取消処理が失敗
     *
     * @test
     */
    public function commentCancelProcessingFailed()
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
        
        $comments = factory(Comment::class)->create([
            'post_id'  => $post->id,
            'user_id' => $user->id,
        ]);
        
        //$comments_idを空にしてgetリクエストで送信する。
        $comments_id = $comments->id;
        $comments_id = '';
  
        $url = route('comments.delete',$comments_id);
        $this->get($url)
             ->assertStatus(404);

        // データベースに値が登録されているかチェック
        $this->assertDatabaseHas('comments', [
            'id'      => $comments->id,
            'post_id' => $comments->post_id,
            'user_id' => $comments->user_id,
        ]);
    }
}
