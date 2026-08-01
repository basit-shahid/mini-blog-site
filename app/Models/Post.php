<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'series_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'is_markdown',
        'reading_time',
        'status',
        'published_at',
    ];

    protected $casts = [
        'is_markdown' => 'boolean',
        'published_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Route Model Binding
    |--------------------------------------------------------------------------
    */

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Model Events & Helpers
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::saving(function (Post $post) {
            // Recalculate slug if title changed or slug is empty
            if ($post->isDirty('title') || empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }

            // Recalculate reading time if content changed or reading_time is empty
            if ($post->isDirty('content') || empty($post->reading_time)) {
                $post->reading_time = static::calculateReadingTime($post->content);
            }
        });
    }

    protected static function calculateReadingTime(?string $content): int
    {
        if (blank($content)) {
            return 1;
        }

        $cleanContent = strip_tags($content);
        $wordCount = count(preg_split('/\s+/u', trim($cleanContent), -1, PREG_SPLIT_NO_EMPTY));

        return (int) max(1, ceil($wordCount / 200));
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')->whereNotNull('published_at');
    }

    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderByDesc('published_at');
    }
}