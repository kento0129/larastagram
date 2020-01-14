<?php

namespace Tests\Feature\Http\Controllers;

use Auth;
use App\Post;
use App\User;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostsControllerTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * 未認証時はログイン画面にリダイレクト
     *
     * @test
     */
    public function unauthenticatedWhenLoginScreenRedirect()
    {
        $this->assertFalse(Auth::check());
        $this->get('/')
             ->assertRedirect('/login');
    }
    
    /**
     * 投稿一覧画面を表示
     *
     * @test
     */
    public function displayPostsListScreen()
    {
        $user = factory(User::class)->create([
            'password'  => bcrypt('secret')
        ]);
        $this->assertFalse(Auth::check());
        $this->actingAs($user)
             ->assertTrue(Auth::check());
        $this->get('/')
             ->assertStatus(200); 
    }
    
    /**
     * 新規投稿に成功
     *
     * @test
     */
    public function successfulToNewlyPosts()
    {
        $user = factory(User::class)->create([
            'password'  => bcrypt('secret')
        ]);
        $this->actingAs($user);
        $this->assertTrue(Auth::check());
        $url = route('posts');
        Storage::fake('public');
        $file = UploadedFile::fake()->image('public.jpg');
        $response = $this->post($url, [
            'caption' => 'テスト',
            'post_photo' => $file
        ]);
        $post = Post::where('user_id',$user->id)->first();
        
        // データベースに値が登録されているかチェック
        $this->assertDatabaseHas('posts', [
            'caption' => $post->caption,
            'post_photo' => $post->post_photo,
        ]);
        
        //ファイルが登録されているかチェック
        Storage::disk('public')->assertExists('post_images/'.$post->post_photo);
    }
    
    /**
     * 新規投稿に失敗
     *
     * @test
     */
    public function failedToNewlyPosts()
    {
        $user = factory(User::class)->create([
            'password'  => bcrypt('secret')
        ]);
        $this->actingAs($user);
        $this->assertTrue(Auth::check());
        $url = route('posts');
        $this->post($url, [
            'caption' => '',
            'post_photo' => ''
        ])
             ->assertSessionHasErrors(array('caption', 'post_photo'));
             
        // データベースに値が登録されていないかチェック
        $this->assertDatabaseMissing('posts', [
            'caption' => '',
            'post_photo' => ''
        ]);
    }
    
    /**
     * 投稿の削除に成功
     *
     * @test
     */
    public function successfulDeletingPosts()
    {
        $user = factory(User::class)->create([
            'password'  => bcrypt('secret')
        ]);
        $this->actingAs($user);
        $this->assertTrue(Auth::check());
        $file = UploadedFile::fake()->image(date('YmdHis'). '_' .'public.jpg');
        
        Storage::disk('public')->putFileAs('post_images', $file, $file->getClientOriginalName(), 'public');
        
        $post = factory(Post::class)->create([
            'user_id'  => $user->id,
            'post_photo' => $file->getClientOriginalName(),
        ]);
        
        // データベースに値が登録されているかチェック
        Storage::disk('public')->assertExists('post_images/'.$post->post_photo);
        
        
        $url = route('posts.delete',$post->id);
        $this->get($url)
             ->assertRedirect('/');
             
        // データベースに値が登録されていないかチェック
        $this->assertDatabaseMissing('posts', [
            'caption' => $post->caption,
            'post_photo' => $post->post_photo,
            'user_id' => $user->id,
        ]);
    }
    
    /**
     * 投稿の削除に失敗
     *
     * @test
     */
    public function failedDeletingPosts()
    {
        $user = factory(User::class)->create([
            'password'  => bcrypt('secret')
        ]);
        $this->actingAs($user);
        $this->assertTrue(Auth::check());
        $file = UploadedFile::fake()->image(date('YmdHis'). '_' .'public.jpg');
        Storage::disk('public')->putFileAs('post_images', $file, $file->getClientOriginalName(), 'public');
        $post = factory(Post::class)->create([
            'user_id'  => $user->id,
            'post_photo' => $file->getClientOriginalName(),
        ]);
        Storage::disk('public')->assertExists('post_images/'.$post->post_photo);
        $url = route('posts.delete',null);
        $this->get($url)
             ->assertStatus(404);
             
        // データベースに値が登録されているかチェック
        $this->assertDatabaseHas('posts', [
            'caption' => $post->caption,
            'post_photo' => $post->post_photo,
            'user_id' => $user->id,
        ]);
    }
}
