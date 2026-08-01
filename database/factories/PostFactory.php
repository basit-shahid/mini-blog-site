<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Series;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    public function definition(): array
    {
        $title   = fake()->sentence(6);
        $content = collect(fake()->paragraphs(8))->implode("\n\n");

        return [
            'user_id'      => User::factory(),
            'category_id'  => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'series_id'    => null, // set explicitly when needed, most posts aren't in a series
            'title'        => $title,
            'slug'         => Str::slug($title) . '-' . fake()->unique()->numberBetween(1, 100000),
            'excerpt'      => fake()->sentence(15),
            'content'      => $content,
            'is_markdown'  => true,
            'status'       => 'published',
            'published_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }

    // --- State: draft posts, for testing unpublished content ---
    public function draft(): static
    {
        return $this->state(fn () => [
            'status'       => 'draft',
            'published_at' => null,
        ]);
    }

    // --- State: attach to a series ---
    public function inSeries(): static
    {
        return $this->state(fn () => [
            'series_id' => Series::factory(),
        ]);
    }
}