<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Series;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // A known author you can log in as
        $author = User::factory()->create([
            'name'  => 'Test Author',
            'email' => 'author@example.com',
            'role'  => 'author',
            'password'=>'password',
        ]);

        // Shared pools — created once, reused across posts
        $categories = Category::factory(6)->create();
        $tags       = Tag::factory(10)->create();
        $series     = Series::factory(2)->create();

        // Standalone published posts
        Post::factory(15)
            ->create([
                'user_id'     => $author->id,
                'category_id' => fn () => $categories->random()->id,
            ])
            ->each(function (Post $post) use ($tags) {
                $post->tags()->attach(
                    $tags->random(rand(1, 4))->pluck('id')
                );
            });

        // A few posts that belong to a series
        Post::factory(5)
            ->create([
                'user_id'     => $author->id,
                'series_id'   => fn () => $series->random()->id,
                'category_id' => fn () => $categories->random()->id,
            ])
            ->each(function (Post $post) use ($tags) {
                $post->tags()->attach(
                    $tags->random(rand(1, 3))->pluck('id')
                );
            });

        // A couple of drafts
        Post::factory(3)
            ->draft()
            ->create([
                'user_id'     => $author->id,
                'category_id' => fn () => $categories->random()->id,
            ]);
    }
}