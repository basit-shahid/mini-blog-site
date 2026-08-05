<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryPostTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_page_displays_published_posts_in_that_category(): void
    {
        $author = User::factory()->create(['role' => 'author']);
        $category = Category::factory()->create();

        $publishedInCategory = Post::factory()->create([
            'user_id'     => $author->id,
            'category_id' => $category->id,
            'status'      => 'published',
            'published_at' => now(),
        ]);

        $otherCategory = Category::factory()->create();
        $postInOtherCategory = Post::factory()->create([
            'user_id'     => $author->id,
            'category_id' => $otherCategory->id,
            'status'      => 'published',
            'published_at' => now(),
        ]);

        $response = $this->get(route('categories.show', $category));

        $response->assertOk();
        $response->assertSee($publishedInCategory->title);
        $response->assertSee($category->name);
        $response->assertDontSee($postInOtherCategory->title);
    }

    public function test_category_page_hides_draft_posts(): void
    {
        $author = User::factory()->create(['role' => 'author']);
        $category = Category::factory()->create();

        $draft = Post::factory()->draft()->create([
            'user_id'     => $author->id,
            'category_id' => $category->id,
        ]);

        $published = Post::factory()->create([
            'user_id'     => $author->id,
            'category_id' => $category->id,
            'status'      => 'published',
            'published_at' => now(),
        ]);

        $response = $this->get(route('categories.show', $category));

        $response->assertOk();
        $response->assertSee($published->title);
        $response->assertDontSee($draft->title);
    }

    public function test_category_page_shows_empty_state_when_no_published_posts(): void
    {
        $category = Category::factory()->create();

        $response = $this->get(route('categories.show', $category));

        $response->assertOk();
        $response->assertSee('No posts published yet in this category.');
    }
}