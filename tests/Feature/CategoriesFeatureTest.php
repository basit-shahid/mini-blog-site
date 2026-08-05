<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoriesFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_categories_index_shows_categories()
    {
        $category = Category::factory()->create(['name' => 'Testing Category']);

        $response = $this->get(route('categories.index'));

        $response->assertStatus(200);
        $response->assertSee('Testing Category');
    }

    public function test_category_show_shows_published_posts()
    {
        $category = Category::factory()->create();
        $post = Post::factory()->for($category)->create([
            'title' => 'My Published Post',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->get(route('categories.show', $category));

        $response->assertStatus(200);
        $response->assertSee('My Published Post');
    }
}
