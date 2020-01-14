<?php

namespace Tests\Feature\Http\Controllers;

use Auth;
use App\User;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UsersControllerTest extends TestCase
{
    // use DatabaseTransactions;
        
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
        $this->assertFalse(Auth::check());
        
        //ユーザ詳細画面
        $this->get('/users/'.$user->id)
             ->assertRedirect('/login');
             
        //ユーザ編集画面
        $this->get('/users/edit')
             ->assertRedirect('/login');
             
        //パスワード編集画面
        $this->get('/users/password')
             ->assertRedirect('/login');     
    }
    
    /**
     * ユーザー詳細画面を表示
     *
     * @test
     */
    public function displayUserDetailScreen()
    {
        $user = factory(User::class)->create([
            'password'  => bcrypt('secret')
        ]);
        $this->assertFalse(Auth::check());
        $this->actingAs($user)
             ->assertTrue(Auth::check());
        $this->get('/users/'.$user->id)
             ->assertStatus(200);
    }
    
    /**
     * ユーザー編集画面を表示
     *
     * @test
     */
    public function displayUserEditScreen()
    {
        $user = factory(User::class)->create([
            'password'  => bcrypt('secret')
        ]);
        $this->assertFalse(Auth::check());
        $this->actingAs($user)
             ->assertTrue(Auth::check());
        $this->get('/users/edit')
             ->assertStatus(200); 
    }
    
    /**
     * ユーザー更新に成功
     *
     * @test
     */
    public function successfulToUserUpdate()
    {
        $user = factory(User::class)->create([
            'password'  => bcrypt('secret')
        ]);
        $this->actingAs($user);
        $this->assertTrue(Auth::check());
        $url = route('users.update');
        Storage::fake('public');
        $file = UploadedFile::fake()->image('public.jpg');
        // $response = $this->post($url, [
            // 'name' => 'テスト太郎',
            // 'user_name' => 'test_tarou',
            // 'email' => 'test_tarou@test.com',
            // 'profile_photo' => $file,
        // ]);
        // dd($response);
        // $user = User::where('id',$user->id)->first();
        // $this->assertDatabaseHas('users', [
        //     'name' => 'テスト太郎',
        //     'user_name' => 'test_tarou',
        //     'email' => 'test_tarou@test.com',
        //     'profile_photo' => $user->profile_photo,
        // ]);
    }
}
