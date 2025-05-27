<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Brand;
use App\Models\User;
use Tests\TestCase;

class BrandControllerTest extends TestCase
{

    public function test_index_displays_brands()
    {
        $user = User::factory()->create();
        $brands = Brand::factory()->count(3)->create();

        $response = $this->actingAs($user)->get(route('brands.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.brands.list');
        $response->assertViewHas('brands', function ($viewBrands) use ($brands) {
            return $viewBrands->count() === $brands->count();
        });
    }

    public function test_create_displays_form()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('brands.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.brands.add');
    }

    public function test_store_creates_brand()
    {
        $user = User::factory()->create();
        $data = [
            'name' => 'New Brand',
            'description' => 'Brand description',
        ];

        $response = $this->actingAs($user)->post(route('brands.store'), $data);

        $response->assertRedirect(route('brands.index'));
        $this->assertDatabaseHas('brands', $data);
    }

    public function test_store_validation_errors()
    {
        $user = User::factory()->create();
        $data = ['name' => '']; // Missing required fields

        $response = $this->actingAs($user)->post(route('brands.store'), $data);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_edit_displays_form()
    {
        $user = User::factory()->create();
        $brand = Brand::factory()->create();

        $response = $this->actingAs($user)->get(route('brands.edit', $brand));

        $response->assertStatus(200);
        $response->assertViewIs('admin.brands.edit');
        $response->assertViewHas('brand', $brand);
    }

    public function test_update_updates_brand()
    {
        $user = User::factory()->create();
        $brand = Brand::factory()->create();
        $data = [
            'name' => 'Updated Brand',
            'description' => 'Updated description',
        ];

        $response = $this->actingAs($user)->put(route('brands.update', $brand), $data);

        $response->assertRedirect(route('brands.index'));
        $this->assertDatabaseHas('brands', $data);
    }

    public function test_update_validation_errors()
    {
        $user = User::factory()->create();
        $brand = Brand::factory()->create();
        $data = ['name' => '']; // Missing required fields

        $response = $this->actingAs($user)->put(route('brands.update', $brand), $data);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_toggle_status_changes_status()
    {
        $user = User::factory()->create();
        $brand = Brand::factory()->create(['status' => Brand::STATUS_ACTIVE]);

        $response = $this->actingAs($user)->post(route('brands.toggleStatus', $brand));

        $response->assertRedirect(route('brands.index'));
        $this->assertDatabaseHas('brands', [
            'id' => $brand->id,
            'status' => Brand::STATUS_INACTIVE,
        ]);
    }
}