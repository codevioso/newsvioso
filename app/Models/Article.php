<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use App\Helpers\SlugHelper;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'status',
        'scheduled_at',
        'published_at',
        'featured_image',
        'created_by',
        'updated_by',
        'category_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_active'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'scheduled_at' => 'datetime',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
            $article->created_by = auth()->id();
            $article->updated_by = auth()->id();
        });

        static::updating(function ($article) {
            $article->updated_by = auth()->id();
            
            // Generate slug if title changed and article is not published
            if ($article->isDirty('title') && $article->status !== 'published') {
                $article->slug = Str::slug($article->title);
            }
            
            // Set published_at when status changes to published
            if ($article->isDirty('status') && $article->status === 'published' && !$article->published_at) {
                $article->published_at = now();
            }
        });
    }

    /**
     * Generate a unique multilingual slug from title.
     */
    public static function generateUniqueSlug(string $title): string
    {
        return Str::slug($title);
    }

    /**
     * Generate a unique multilingual slug from title (excluding specific ID).
     */
    public static function generateUniqueSlugExcluding(string $title, int $excludeId): string
    {
        return Str::slug($title);
    }

    /**
     * Get the user who created the article.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the article.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the category that owns the article.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the tags for the article.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Get the featured image URL.
     */
    public function getFeaturedImageUrlAttribute(): ?string
    {
        if (!$this->featured_image) {
            return null;
        }

        return str_starts_with($this->featured_image, 'http') 
            ? $this->featured_image 
            : "/storage/{$this->featured_image}";
    }

    /**
     * Scope a query to only include published articles.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('is_active', 1)
                    ->where(function ($q) {
                        $q->whereNull('published_at')
                          ->orWhere('published_at', '<=', now());
                    });
    }

    /**
     * Scope a query to only include draft articles.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope a query to only include scheduled articles.
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled')
                    ->where('scheduled_at', '>', now());
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope a query to filter by author.
     */
    public function scopeByAuthor($query, $authorId)
    {
        return $query->where('created_by', $authorId);
    }

    /**
     * Scope a query to search articles.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('excerpt', 'like', "%{$search}%")
              ->orWhere('content', 'like', "%{$search}%");
        });
    }
}