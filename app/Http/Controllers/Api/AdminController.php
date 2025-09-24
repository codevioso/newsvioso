<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Constants\UserRole;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Display a listing of admins
     */
    public function index(Request $request): JsonResponse
    {
        $query = User::query();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }

        // Filter by status (active/inactive)
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status === 'active');
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $admins = $query->paginate($perPage);

        // Transform the data
        $transformedAdmins = [];
        foreach ($admins->items() as $admin) {
            $transformedAdmins[] = [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => $admin->role,
                'role_display_name' => UserRole::getDisplayName($admin->role),
                'is_active' => $admin->is_active ?? true,
                'avatar' => $admin->avatar,
                'avatar_url' => $admin->avatar ? Storage::url($admin->avatar) : null,
                'created_at' => $admin->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $admin->updated_at->format('Y-m-d H:i:s'),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'data' => $transformedAdmins,
                'current_page' => $admins->currentPage(),
                'last_page' => $admins->lastPage(),
                'per_page' => $admins->perPage(),
                'total' => $admins->total(),
            ],
            'message' => 'Admins retrieved successfully'
        ]);
    }

    /**
     * Store a newly created admin
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', 'string', Rule::in(UserRole::all())],
            'is_active' => 'boolean',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $admin = new User();
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->password = Hash::make($request->password);
            $admin->role = $request->role;
            $admin->is_active = $request->get('is_active', true);

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                $filename = time() . '_' . $avatar->getClientOriginalName();
                $avatar->storeAs('public/avatars', $filename);
                $admin->avatar = "avatars/{$filename}";
            }

            $admin->save();

            // Transform the response data
            $adminData = [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => $admin->role,
                'role_display_name' => UserRole::getDisplayName($admin->role),
                'is_active' => $admin->is_active,
                'avatar' => $admin->avatar,
                'avatar_url' => $admin->avatar ? Storage::url($admin->avatar) : null,
                'created_at' => $admin->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $admin->updated_at->format('Y-m-d H:i:s'),
            ];

            return response()->json([
                'success' => true,
                'data' => $adminData,
                'message' => 'Admin created successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create admin',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified admin
     */
    public function show(User $admin): JsonResponse
    {
        $adminData = [
            'id' => $admin->id,
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => $admin->role,
            'role_display_name' => UserRole::getDisplayName($admin->role),
            'is_active' => $admin->is_active ?? true,
            'avatar' => $admin->avatar,
            'avatar_url' => $admin->avatar ? Storage::url($admin->avatar) : null,
            'created_at' => $admin->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $admin->updated_at->format('Y-m-d H:i:s'),
        ];

        return response()->json([
            'success' => true,
            'data' => $adminData,
            'message' => 'Admin retrieved successfully'
        ]);
    }

    /**
     * Update the specified admin
     */
    public function update(Request $request, User $admin): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($admin->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => ['required', 'string', Rule::in(UserRole::all())],
            'is_active' => 'between:0,1',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->role = $request->role;
            $admin->is_active = $request->get('is_active', 0);

            // Update password if provided
            if ($request->filled('password')) {
                $admin->password = Hash::make($request->password);
            }

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                // Delete old avatar if exists
                if ($admin->avatar && Storage::disk('public')->exists($admin->avatar)) {
                    Storage::disk('public')->delete($admin->avatar);
                }

                $avatar = $request->file('avatar');
                $filename = time() . '_' . $avatar->getClientOriginalName();
                $avatar->storeAs('public/avatars', $filename);
                $admin->avatar = "avatars/{$filename}";
            }

            $admin->save();

            // Transform the response data
            $adminData = [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => $admin->role,
                'role_display_name' => UserRole::getDisplayName($admin->role),
                'is_active' => $admin->is_active,
                'avatar' => $admin->avatar,
                'avatar_url' => $admin->avatar ? Storage::url($admin->avatar) : null,
                'created_at' => $admin->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $admin->updated_at->format('Y-m-d H:i:s'),
            ];

            return response()->json([
                'success' => true,
                'data' => $adminData,
                'message' => 'Admin updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update admin',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified admin
     */
    public function destroy(User $admin): JsonResponse
    {
        try {
            // Prevent deletion of the current user
            if ($admin->id === auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot delete your own account'
                ], 403);
            }

            // Delete avatar if exists
            if ($admin->avatar && Storage::disk('public')->exists($admin->avatar)) {
                Storage::disk('public')->delete($admin->avatar);
            }

            $admin->delete();

            return response()->json([
                'success' => true,
                'message' => 'Admin deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete admin',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available roles for admin creation/editing
     */
    public function getRoles(): JsonResponse
    {
        $roles = collect(UserRole::all())->map(function ($role) {
            return [
                'value' => $role,
                'label' => UserRole::getDisplayName($role)
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $roles,
            'message' => 'Roles retrieved successfully'
        ]);
    }
}
