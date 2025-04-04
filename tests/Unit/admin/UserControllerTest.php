<?php

namespace Tests\Unit\admin;

use Tests\TestCase;
use App\Models\Users;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserControllerTest extends TestCase
{
    /**
     * Test index method.
     */
    public function testIndex()
    {
        $response = $this->get(route('users.list'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.users.list');
    }
}