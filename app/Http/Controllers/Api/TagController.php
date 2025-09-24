<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    /**
     * Display a listing of tags
     */
    public function index(Request $request): JsonResponse
    {
        $query = Tag::query();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $tags = $query->paginate($perPage);

        // Transform the data
        $transformedTags = [];
        foreach ($tags->items() as $tag) {
            $transformedTags[] = [
                'id' => $tag->id,
                'title' => $tag->title,
                'is_active' => $tag->is_active,
                'created_at' => $tag->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $tag->updated_at->format('Y-m-d H:i:s'),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'data' => $transformedTags,
                'current_page' => $tags->currentPage(),
                'last_page' => $tags->lastPage(),
                'per_page' => $tags->perPage(),
                'total' => $tags->total(),
            ],
            'message' => 'Tags retrieved successfully'
        ]);
    }

    /**
     * Store a newly created tag
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|unique:tags,title',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $tag = Tag::create([
                'title' => $request->title,
                'is_active' => $request->get('is_active', true),
            ]);

            $tagData = [
                'id' => $tag->id,
                'title' => $tag->title,
                'is_active' => $tag->is_active,
                'created_at' => $tag->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $tag->updated_at->format('Y-m-d H:i:s'),
            ];

            return response()->json([
                'success' => true,
                'data' => $tagData,
                'message' => 'Tag created successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create tag',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified tag
     */
    public function show(Tag $tag): JsonResponse
    {
        $tagData = [
            'id' => $tag->id,
            'title' => $tag->title,
            'is_active' => $tag->is_active,
            'created_at' => $tag->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $tag->updated_at->format('Y-m-d H:i:s'),
        ];

        return response()->json([
            'success' => true,
            'data' => $tagData,
            'message' => 'Tag retrieved successfully'
        ]);
    }

    /**
     * Update the specified tag
     */
    public function update(Request $request, Tag $tag): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|unique:tags,title,' . $tag->id,
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $tag->update([
                'title' => $request->title,
                'is_active' => $request->get('is_active', true),
            ]);

            $tagData = [
                'id' => $tag->id,
                'title' => $tag->title,
                'is_active' => $tag->is_active,
                'created_at' => $tag->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $tag->updated_at->format('Y-m-d H:i:s'),
            ];

            return response()->json([
                'success' => true,
                'data' => $tagData,
                'message' => 'Tag updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update tag',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified tag (soft delete)
     */
    public function destroy(Tag $tag): JsonResponse
    {
        try {
            $tag->delete();

            return response()->json([
                'success' => true,
                'message' => 'Tag deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete tag',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
