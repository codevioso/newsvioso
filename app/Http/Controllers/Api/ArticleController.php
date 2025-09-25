<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Helpers\SlugHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Display a listing of articles.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Article::with(['category', 'tags', 'creator', 'updater']);

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $query->search($request->search);
        }

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->has('category_id') && !empty($request->category_id)) {
            $query->byCategory($request->category_id);
        }

        // Filter by author
        if ($request->has('author_id') && !empty($request->author_id)) {
            $query->byAuthor($request->author_id);
        }

        // Filter by date range
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->where('created_at', '<=', $request->date_to);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $articles = $query->paginate($perPage);

        // Transform the data
        $transformedArticles = [];
        foreach ($articles->items() as $article) {
            $transformedArticles[] = [
                'id' => $article->id,
                'title' => $article->title,
                'slug' => $article->slug,
                'excerpt' => $article->excerpt,
                'status' => $article->status,
                'scheduled_at' => $article->scheduled_at?->format('Y-m-d H:i:s'),
                'published_at' => $article->published_at?->format('Y-m-d H:i:s'),
                'featured_image' => $article->featured_image,
                'featured_image_url' => $article->featured_image_url,
                'category' => $article->category ? [
                    'id' => $article->category->id,
                    'title' => $article->category->title,
                ] : null,
                'tags' => $article->tags->map(function ($tag) {
                    return [
                        'id' => $tag->id,
                        'title' => $tag->title,
                    ];
                }),
                'author' => $article->creator ? [
                    'id' => $article->creator->id,
                    'name' => $article->creator->name,
                ] : null,
                'meta_title' => $article->meta_title,
                'meta_description' => $article->meta_description,
                'meta_keywords' => $article->meta_keywords,
                'is_active' => $article->is_active,
                'created_at' => $article->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $article->updated_at->format('Y-m-d H:i:s'),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'data' => $transformedArticles,
                'current_page' => $articles->currentPage(),
                'last_page' => $articles->lastPage(),
                'per_page' => $articles->perPage(),
                'total' => $articles->total(),
            ],
            'message' => 'Articles retrieved successfully'
        ]);
    }

    /**
     * Store a newly created article.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'status' => 'required|in:draft,scheduled,published',
            'scheduled_at' => 'nullable|date|after:now',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'is_active' => 'between:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Validate scheduled_at when status is scheduled
        if ($request->status === 'scheduled' && !$request->scheduled_at) {
            return response()->json([
                'success' => false,
                'message' => 'Scheduled date is required when status is scheduled',
                'errors' => ['scheduled_at' => ['Scheduled date is required when status is scheduled']]
            ], 422);
        }

        try {
            $data = $request->only([
                'title', 'excerpt', 'content', 'category_id',
                'status', 'scheduled_at', 'meta_title', 'meta_description',
                'meta_keywords', 'is_active'
            ]);

            // Handle featured image upload
            if ($request->hasFile('featured_image')) {
                $data['featured_image'] = $this->storeFeaturedImage($request->file('featured_image'));
            }

            // Process content for base64 images
            $data['content'] = $this->processContentImages($data['content']);

            // Set published_at if status is published
            if ($data['status'] === 'published') {
                $data['published_at'] = now();
            }

            $article = Article::create($data);

            // Attach tags
            if ($request->has('tags')) {
                $article->tags()->attach($request->tags);
            }

            // Load relationships
            $article->load(['category', 'tags', 'creator', 'updater']);

            $articleData = [
                'id' => $article->id,
                'title' => $article->title,
                'slug' => $article->slug,
                'excerpt' => $article->excerpt,
                'content' => $article->content,
                'status' => $article->status,
                'scheduled_at' => $article->scheduled_at?->format('Y-m-d H:i:s'),
                'published_at' => $article->published_at?->format('Y-m-d H:i:s'),
                'featured_image' => $article->featured_image,
                'featured_image_url' => $article->featured_image_url,
                'category' => $article->category ? [
                    'id' => $article->category->id,
                    'title' => $article->category->title,
                ] : null,
                'tags' => $article->tags->map(function ($tag) {
                    return [
                        'id' => $tag->id,
                        'title' => $tag->title,
                    ];
                }),
                'author' => $article->creator ? [
                    'id' => $article->creator->id,
                    'name' => $article->creator->name,
                ] : null,
                'meta_title' => $article->meta_title,
                'meta_description' => $article->meta_description,
                'meta_keywords' => $article->meta_keywords,
                'is_active' => $article->is_active,
                'created_at' => $article->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $article->updated_at->format('Y-m-d H:i:s'),
            ];

            return response()->json([
                'success' => true,
                'data' => $articleData,
                'message' => 'Article created successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create article',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified article.
     */
    public function show(Article $article): JsonResponse
    {
        $article->load(['category', 'tags', 'creator', 'updater']);

        $articleData = [
            'id' => $article->id,
            'title' => $article->title,
            'slug' => $article->slug,
            'excerpt' => $article->excerpt,
            'content' => $article->content,
            'status' => $article->status,
            'scheduled_at' => $article->scheduled_at?->format('Y-m-d H:i:s'),
            'published_at' => $article->published_at?->format('Y-m-d H:i:s'),
            'featured_image' => $article->featured_image,
            'featured_image_url' => $article->featured_image_url,
            'category' => $article->category ? [
                'id' => $article->category->id,
                'title' => $article->category->title,
            ] : null,
            'tags' => $article->tags->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'title' => $tag->title,
                ];
            }),
            'author' => $article->creator ? [
                'id' => $article->creator->id,
                'name' => $article->creator->name,
            ] : null,
            'meta_title' => $article->meta_title,
            'meta_description' => $article->meta_description,
            'meta_keywords' => $article->meta_keywords,
            'is_active' => $article->is_active,
            'created_at' => $article->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $article->updated_at->format('Y-m-d H:i:s'),
        ];

        return response()->json([
            'success' => true,
            'data' => $articleData,
            'message' => 'Article retrieved successfully'
        ]);
    }

    /**
     * Update the specified article.
     */
    public function update(Request $request, Article $article): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'status' => 'required|in:draft,scheduled,published',
            'scheduled_at' => 'nullable|date|after:now',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'is_active' => 'between:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Validate scheduled_at when status is scheduled
        if ($request->status === 'scheduled' && !$request->scheduled_at) {
            return response()->json([
                'success' => false,
                'message' => 'Scheduled date is required when status is scheduled',
                'errors' => ['scheduled_at' => ['Scheduled date is required when status is scheduled']]
            ], 422);
        }

        try {
            $data = $request->only([
                'title', 'excerpt', 'content', 'category_id',
                'status', 'scheduled_at', 'meta_title', 'meta_description',
                'meta_keywords', 'is_active'
            ]);

            // Handle featured image upload
            if ($request->hasFile('featured_image')) {
                // Delete old featured image
                if ($article->featured_image) {
                    Storage::disk('public')->delete($article->featured_image);
                }
                $data['featured_image'] = $this->storeFeaturedImage($request->file('featured_image'));
            }

            // Process content for base64 images
            $data['content'] = $this->processContentImages($data['content']);

            // Set published_at if status changes to published
            if ($data['status'] === 'published' && $article->status !== 'published') {
                $data['published_at'] = now();
            }

            $article->update($data);

            // Sync tags
            if ($request->has('tags')) {
                $article->tags()->sync($request->tags);
            }

            // Load relationships
            $article->load(['category', 'tags', 'creator', 'updater']);

            $articleData = [
                'id' => $article->id,
                'title' => $article->title,
                'slug' => $article->slug,
                'excerpt' => $article->excerpt,
                'content' => $article->content,
                'status' => $article->status,
                'scheduled_at' => $article->scheduled_at?->format('Y-m-d H:i:s'),
                'published_at' => $article->published_at?->format('Y-m-d H:i:s'),
                'featured_image' => $article->featured_image,
                'featured_image_url' => $article->featured_image_url,
                'category' => $article->category ? [
                    'id' => $article->category->id,
                    'title' => $article->category->title,
                ] : null,
                'tags' => $article->tags->map(function ($tag) {
                    return [
                        'id' => $tag->id,
                        'title' => $tag->title,
                    ];
                }),
                'author' => $article->creator ? [
                    'id' => $article->creator->id,
                    'name' => $article->creator->name,
                ] : null,
                'meta_title' => $article->meta_title,
                'meta_description' => $article->meta_description,
                'meta_keywords' => $article->meta_keywords,
                'is_active' => $article->is_active,
                'created_at' => $article->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $article->updated_at->format('Y-m-d H:i:s'),
            ];

            return response()->json([
                'success' => true,
                'data' => $articleData,
                'message' => 'Article updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update article',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified article (soft delete).
     */
    public function destroy(Article $article): JsonResponse
    {
        try {
            $article->delete();

            return response()->json([
                'success' => true,
                'message' => 'Article deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete article',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete articles.
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:articles,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            Article::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Articles deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete articles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload image for article content.
     */
    public function uploadImage(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $path = $this->storeContentImage($request->file('image'));
            $url = "/storage/{$path}";

            return response()->json([
                'success' => true,
                'data' => [
                    'url' => $url,
                    'path' => $path,
                ],
                'message' => 'Image uploaded successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload image',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Preview article by slug.
     */
    public function preview(string $slug): JsonResponse
    {
        $article = Article::where('slug', $slug)->first();

        if (!$article) {
            return response()->json([
                'success' => false,
                'message' => 'Article not found'
            ], 404);
        }

        $article->load(['category', 'tags', 'creator']);

        $articleData = [
            'id' => $article->id,
            'title' => $article->title,
            'slug' => $article->slug,
            'excerpt' => $article->excerpt,
            'content' => $article->content,
            'status' => $article->status,
            'published_at' => $article->published_at?->format('Y-m-d H:i:s'),
            'featured_image_url' => $article->featured_image_url,
            'category' => $article->category ? [
                'id' => $article->category->id,
                'title' => $article->category->title,
            ] : null,
            'tags' => $article->tags->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'title' => $tag->title,
                ];
            }),
            'author' => $article->creator ? [
                'id' => $article->creator->id,
                'name' => $article->creator->name,
            ] : null,
            'meta_title' => $article->meta_title,
            'meta_description' => $article->meta_description,
            'meta_keywords' => $article->meta_keywords,
            'created_at' => $article->created_at->format('Y-m-d H:i:s'),
        ];

        return response()->json([
            'success' => true,
            'data' => $articleData,
            'message' => 'Article preview retrieved successfully'
        ]);
    }

    /**
     * Store featured image.
     */
    private function storeFeaturedImage($file): string
    {
        $year = date('Y');
        $month = date('m');
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        
        return $file->storeAs("articles/{$year}/{$month}/featured", $filename, 'public');
    }

    /**
     * Store content image.
     */
    private function storeContentImage($file): string
    {
        $year = date('Y');
        $month = date('m');
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        
        return $file->storeAs("articles/{$year}/{$month}/content", $filename, 'public');
    }

    /**
     * Process content to convert base64 images to stored files.
     */
    private function processContentImages(string $content): string
    {
        // Pattern to match base64 images
        $pattern = '/<img[^>]+src="data:image\/([^;]+);base64,([^"]+)"/';
        
        return preg_replace_callback($pattern, function ($matches) {
            $extension = $matches[1];
            $base64Data = $matches[2];
            
            // Decode base64 data
            $imageData = base64_decode($base64Data);
            
            // Generate filename
            $year = date('Y');
            $month = date('m');
            $filename = time() . '_' . Str::random(10) . '.' . $extension;
            $path = "articles/{$year}/{$month}/content/{$filename}";
            
            // Store the image
            Storage::disk('public')->put($path, $imageData);
            
            // Return the new src
            return 'src="/storage/' . $path . '"';
        }, $content);
    }

    /**
     * Publish an article.
     */
    public function publish(Article $article): JsonResponse
    {
        try {
            $article->update([
                'status' => 'published',
                'published_at' => now(),
                'updated_by' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Article published successfully',
                'data' => $article->load(['category', 'tags', 'creator', 'updater'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to publish article',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Schedule an article.
     */
    public function schedule(Request $request, Article $article): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'scheduled_at' => 'required|date|after:now'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $article->update([
                'status' => 'scheduled',
                'scheduled_at' => $request->scheduled_at,
                'updated_by' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Article scheduled successfully',
                'data' => $article->load(['category', 'tags', 'creator', 'updater'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to schedule article',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
