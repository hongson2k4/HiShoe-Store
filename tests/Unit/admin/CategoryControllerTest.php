<?php

namespace Tests\Feature\Http\Controllers\admin;
use App\Models\Category;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{

    public function test_index_displays_categories()
    {
        $categories = Category::factory()->count(3)->create();

        $response = $this->get(route('category.list'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.index');
        $response->assertViewHas('category', function ($viewCategories) use ($categories) {
            return $viewCategories->count() === $categories->count();
        });
    }

    public function test_create_displays_create_view()
    {
        $response = $this->get(route('category.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.create');
    }

    public function test_store_creates_category()
    {
        $data = [
            'name' => 'New Category',
            'description' => 'Category description',
        ];

        $response = $this->post(route('category.store'), $data);

        $response->assertRedirect(route('category.list'));
        $this->assertDatabaseHas('categories', $data);
    }

    public function test_delete_removes_category()
    {
        $category = Category::factory()->create();

        $response = $this->delete(route('category.delete', $category->id));

        $response->assertRedirect(route('category.list'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_edit_displays_edit_view()
    {
        $category = Category::factory()->create();

        $response = $this->get(route('category.edit', $category->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.edit');
        $response->assertViewHas('category', $category);
    }

    public function test_update_updates_category()
    {
        $category = Category::factory()->create();
        $updatedData = [
            'name' => 'Updated Category',
            'description' => 'Updated description',
        ];

        $response = $this->put(route('category.update', $category->id), $updatedData);

        $response->assertRedirect(route('category.list'));
        $this->assertDatabaseHas('categories', $updatedData);
    }
}