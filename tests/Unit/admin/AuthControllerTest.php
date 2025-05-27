<?php

namespace Tests\Feature\Http\Controllers\admin;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{

    public function test_login_form_redirects_if_authenticated()
    {
        Auth::shouldReceive('check')->once()->andReturn(true);
        Auth::shouldReceive('user')->once()->andReturn((object)['role' => 1]);

        $response = $this->get(route('admin.loginForm'));

        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_login_form_shows_login_view_if_not_authenticated()
    {
        Auth::shouldReceive('check')->once()->andReturn(false);

        $response = $this->get(route('admin.loginForm'));

        $response->assertViewIs('admin.login');
    }

    public function test_login_successful_redirects_to_dashboard()
    {
        $credentials = ['username' => 'admin', 'password' => 'password'];
        Auth::shouldReceive('attempt')->with($credentials)->once()->andReturn(true);
        Auth::shouldReceive('user')->once()->andReturn((object)['role' => 1]);

        $response = $this->post(route('admin.login'), $credentials);

        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_login_fails_with_invalid_credentials()
    {
        $credentials = ['username' => 'admin', 'password' => 'wrongpassword'];
        Auth::shouldReceive('attempt')->with($credentials)->once()->andReturn(false);

        $response = $this->post(route('admin.login'), $credentials);

        $response->assertRedirect(route('admin.loginForm'));
        $response->assertSessionHas('error', 'Sai thông tin đăng nhập!');
    }

    public function test_logout_redirects_to_home()
    {
        Auth::shouldReceive('logout')->once();

        $response = $this->post(route('admin.logout'));

        $response->assertRedirect(route('home'));
    }
}