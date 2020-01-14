<?php

namespace Tests\Feature\Http\Controllers;

use Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegisterControllerTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * ユーザー登録画面を表示
     *
     * @test
     */
    public function displayUserRegistrationScreen()
    {
        $this->get('/register')
             ->assertStatus(200); 
    }
    
    /**
     * ユーザー登録に成功
     *
     * @test
     */
    public function successfulToUserRegistration() 
    {
        $url = route('register');
        $this->post($url, [
            'name' => 'test01',
            'user_name' => 'test01',
            'email' => 'test01@test.com',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ])
             ->assertRedirect('/');
             
        //データベースに値が登録されているかチェック
        $this->assertDatabaseHas('users', [
            'name' => 'test01',
            'user_name' => 'test01',
            'email' => 'test01@test.com',
        ]);
    }
    
    /**
     * ユーザー登録に失敗
     *
     * @test
     */
    public function failedToUserRegistration()
    {
        $url = route('register');
        $this->post($url, [
            'name' => '',
            'user_name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => ''
        ])
             ->assertSessionHasErrors(array('name', 'user_name','email','password'));
             
        // データベースに値が登録されていないかチェック
        $this->assertDatabaseMissing('users', [
            'name' => '',
            'user_name' => '',
            'email' => '',
        ]);
    }
}