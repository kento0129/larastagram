<?php

namespace Tests\Feature\Http\Controllers;

use Auth;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginControllerTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * ログイン画面を表示
     *
     * @test
     */
    public function connectionToLoginScreen()
    {
        $this->get('/login')
             ->assertStatus(200); 
    }
    
    /**
     * 未認証時はログイン画面にリダイレクト
     *
     * @test
     */
    public function unauthenticatedWhenLoginScreenRedirect()
    {
        $this->get('/')
             ->assertRedirect('/login');
    }
    
    /**
     * ログインに成功
     *
     * @test
     */
    public function successfulToLogin() 
    {
        $user = factory(User::class)->create([
            'password'  => bcrypt('secret')
        ]);
        $this->assertFalse(Auth::check());
        $response = $this->post('login', [
            'email'    => $user->email,
            'password' => 'secret'
        ]);
        $this->assertTrue(Auth::check());
        $response->assertRedirect('/');
    }
    
    /**
     * ログインに失敗
     *
     * @test
     */
    public function failureToLogin()
    {
        $user = factory(User::class)->create([
            'password'  => bcrypt('secret')
        ]);
        $this->assertFalse(Auth::check());
        $response = $this->post('login', [
            'email'    => 'test@test.com',
            'password' => 'test'
        ]);
        $this->assertFalse(Auth::check());
        $response->assertSessionHasErrors(["email" => '認証情報が記録と一致しません。',]);
    }
}
