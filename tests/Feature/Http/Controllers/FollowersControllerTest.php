<?php

namespace Tests\Feature\Http\Controllers;

use Auth;
use App\Follower;
use App\User;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FollowersControllerTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * 未認証時はログイン画面にリダイレクト
     *
     * @test
     */
    public function unauthenticatedWhenLoginScreenRedirect()
    {
        $following_user = factory(User::class)->create();
        $followed_user = factory(User::class)->create();

        $this->assertFalse(Auth::check());
        
        $url = route('followers.posts',$followed_user->id);
        $this->get($url)
             ->assertRedirect('/login');
        
        // データベースに値が登録されていないかチェック
        $this->assertDatabaseMissing('followers', [
            'following_id' => $following_user->id,
            'followed_id' => $followed_user->id,
        ]);
    }
    
    
    /**
     * フォロー登録処理の成功
     *
     * @test
     */
    public function followRegistrationProcessingSuccessful()
    {
        $following_user = factory(User::class)->create();
        $followed_user = factory(User::class)->create();
        $this->assertFalse(Auth::check());
        $this->actingAs($following_user)
             ->assertTrue(Auth::check());

        $url = route('followers.posts',$followed_user->id);
        $this->get($url)
             ->assertRedirect('/');

        // データベースに値が登録されているかチェック
        $this->assertDatabaseHas('followers', [
            'following_id' => $following_user->id,
            'followed_id' => $followed_user->id,
        ]);
    }
    
    /**
     * フォロー登録処理の失敗
     *
     * @test
     */
    public function followRegistrationProcessingFailed()
    {
        $following_user = factory(User::class)->create();
        $followed_user = factory(User::class)->create();
        $this->assertFalse(Auth::check());
        $this->actingAs($following_user)
             ->assertTrue(Auth::check());

        $url = route('followers.posts',null);
        $this->get($url)
             ->assertStatus(404);

        // データベースに値が登録されていないかチェック
        $this->assertDatabaseMissing('followers', [
            'following_id' => $following_user->id,
            'followed_id' => $followed_user->id,
        ]);
    }
    
    /**
     * フォロー取消処理の成功
     *
     * @test
     */
    public function followResetProcessingSuccessful()
    {
        $user = factory(User::class)->create();
        $this->assertFalse(Auth::check());
        $this->actingAs($user)
             ->assertTrue(Auth::check());
             
        $follower = factory(Follower::class)->create([
            'following_id' => $user->id,
        ]);

        $url = route('followers.delete',$follower->followed_id);
        $this->get($url)
             ->assertRedirect('/');

        // データベースに値が登録されていないかチェック
        $this->assertDatabaseMissing('followers', [
            'following_id' => $follower->following_id,
            'followed_id' => $follower->followed_id,
        ]);
    }
    
    /**
     * フォロー取消処理の失敗
     *
     * @test
     */
    public function followResetProcessingFailed()
    {
        $user = factory(User::class)->create();
        $this->assertFalse(Auth::check());
        $this->actingAs($user)
             ->assertTrue(Auth::check());
             
        $follower = factory(Follower::class)->create([
            'following_id' => $user->id,
        ]);

        $url = route('followers.delete',null);
        $this->get($url)
             ->assertStatus(404);

        // データベースに値が登録されているかチェック
        $this->assertDatabaseHas('followers', [
            'following_id' => $follower->following_id,
            'followed_id' => $follower->followed_id,
        ]);
    }
}
