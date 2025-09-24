<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Get dashboard data
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'message' => 'Dashboard data retrieved successfully',
            'data' => [
                'user' => [
                    'name' => $user->name,
                    'role' => $user->role,
                    'role_display_name' => $user->getRoleDisplayName(),
                ],
                'current_time' => now()->format('Y-m-d H:i:s'),
                'welcome_message' => "Welcome back, {$user->name}! You are logged in as {$user->getRoleDisplayName()}.",
            ],
        ]);
    }
}
