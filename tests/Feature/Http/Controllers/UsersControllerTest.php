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
        $response = $this->post($url, [
            'id' => $user->id,
            'name' => 'テスト太郎',
            'user_name' => 'test_tarou',
            'email' => 'test_tarou@test.com',
            'profile_photo' => $file,
        ]);
        $user = User::where('id',$user->id)->first();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'テスト太郎',
            'user_name' => 'test_tarou',
            'email' => 'test_tarou@test.com',
            'profile_photo' => $user->profile_photo,
        ]);
        // Storage::disk('public')->assertExists('user_images/'.$user->profile_photo);
    }
    
    /**
     * ユーザー更新に失敗
     *
     * @test
     */
    public function failedToUserUpdate()
    {
        $user = factory(User::class)->create([
            'password'  => bcrypt('secret')
        ]);
        $this->actingAs($user);
        $this->assertTrue(Auth::check());
        $url = route('users.update');
        $this->post($url, [
            'id' => $user->id,
            'name' => '',
            'user_name' => '',
            'email' => '',
            'profile_photo' => null
        ])
             ->assertSessionHasErrors(array('name', 'user_name', 'email', 'profile_photo'));
        $user = User::where('id',$user->id)->first();
        $this->assertDatabaseMissing('users', [
            'name' => '',
            'user_name' => '',
            'email' => '',
            'profile_photo' => '',
        ]);
    }
    
    /**
     * プロフィール画像の更新に成功
     *
     * @test
     */
    public function successfulUpdateProfileImage()
    {
        //プロフィール画像投稿済みのデータを作成
        Storage::fake('public');
        $file = UploadedFile::fake()->image('public.jpg');
        $user = factory(User::class)->create([
            'password'  => bcrypt('secret'),
            'profile_photo' => $file,
        ]);
        
        $this->actingAs($user);
        $this->assertTrue(Auth::check());
        $url = route('users.update');
        
        //新たなプロフィール画像を投稿
        // Storage::fake('public');
        // $file = UploadedFile::fake()->image('public.jpg');
        // $response = $this->post($url, [
        //     'id' => $user->id,
        //     'name' => 'テスト太郎11',
        //     'user_name' => 'test_tarou11',
        //     'email' => 'test_tarou11@test.com',
        //     'profile_photo' => $file,
        // ]);
        
        // Storage::disk('public')->assertExists('user_images/'.$/user->post_photo);
        
        // $user = User::where('id',$user->id)->first();
        // $this->assertDatabaseHas('users', [
        //     'id' => $user->id,
        //     'name' => 'テスト太郎',
        //     'user_name' => 'test_tarou',
        //     'email' => 'test_tarou@test.com',
        //     'profile_photo' => $user->profile_photo,
        // ]);
    }

    /**
     * プロフィール画像の更新に失敗
     *
     * @test
     */
    public function failedUpdateProfileImage()
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
        //     'id' => $user->id,
        //     'name' => 'テスト太郎',
        //     'user_name' => 'test_tarou',
        //     'email' => 'test_tarou@test.com',
        //     'profile_photo' => $file,
        // ]);
        // $user = User::where('id',$user->id)->first();
        // $this->assertDatabaseHas('users', [
        //     'id' => $user->id,
        //     'name' => 'テスト太郎',
        //     'user_name' => 'test_tarou',
        //     'email' => 'test_tarou@test.com',
        //     'profile_photo' => $user->profile_photo,
        // ]);
    }

    /**
     * パスワード編集画面を表示
     *
     * @test
     */
    public function displayPasswordEditScreen()
    {
        $user = factory(User::class)->create([
            'password'  => bcrypt('secret')
        ]);
        $this->assertFalse(Auth::check());
        $this->actingAs($user)
             ->assertTrue(Auth::check());
        $this->get('/users/password')
             ->assertStatus(200);
    }

    /**
     * パスワード変更に成功
     *
     * @test
     */
    public function successfulToPasswordChange()
    {
        $user = factory(User::class)->create([
            'password'  => bcrypt('secret')
        ]);
        $this->actingAs($user);
        $this->assertTrue(Auth::check());
        $url = route('users.password.change');
        $this->post($url, [
            'id' => $user->id,
            'old_password' => 'secret',
            'new_password' => 'secretsecret',
            'new_password_confirmation' => 'secretsecret',
        ]);
        $user = User::where('id',$user->id)->first();
        $this->assertDatabaseHas('users', [
                'id' => $user->id,
                'password' => $user->password,
        ]);
    }   
    
    /**
     * パスワード変更に失敗
     *
     * @test
     */
    public function failedToPasswordChange()
    {
        $user = factory(User::class)->create([
            'password'  => bcrypt('secret')
        ]);
        $this->actingAs($user);
        $this->assertTrue(Auth::check());
        $url = route('users.password.change');
        $this->post($url, [
            'id' => $user->id,
            'old_password' => '',
            'new_password' => '',
            'new_password_confirmation' => '',
        ])
             ->assertSessionHasErrors(array('old_password', 'new_password','new_password_confirmation'));
        $user = User::where('id',$user->id)->first();
        $this->assertDatabaseMissing('users', [
                'id' => $user->id,
                'password' => '',
        ]);
    }  
}
