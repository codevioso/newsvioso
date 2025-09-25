<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\ArticleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public authentication routes
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Authentication routes
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });

    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Profile routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show']);
        Route::post('/', [ProfileController::class, 'update']);
        Route::post('/change-password', [ProfileController::class, 'changePassword']);
    });

    // Super Admin routes
    Route::middleware('role:super_admin')->prefix('admin')->group(function () {
        // Admin management routes
        Route::get('/admins', [AdminController::class, 'index']);
        Route::post('/admins', [AdminController::class, 'store']);
        Route::get('/admins/{admin}', [AdminController::class, 'show']);
        Route::put('/admins/{admin}', [AdminController::class, 'update']);
        Route::delete('/admins/{admin}', [AdminController::class, 'destroy']);
        Route::get('/roles', [AdminController::class, 'getRoles']);
    });

    // Editor routes
    Route::middleware('role:editor')->prefix('editor')->group(function () {
        // Add editor specific routes here
    });

    // Settings routes (accessible by super_admin and editor)
    Route::middleware('role:super_admin,editor')->prefix('settings')->group(function () {
        // Categories routes
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::get('/categories/{category}', [CategoryController::class, 'show']);
        Route::put('/categories/{category}', [CategoryController::class, 'update']);
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
        
        // Tags routes
        Route::get('/tags', [TagController::class, 'index']);
        Route::post('/tags', [TagController::class, 'store']);
        Route::get('/tags/{tag}', [TagController::class, 'show']);
        Route::put('/tags/{tag}', [TagController::class, 'update']);
        Route::delete('/tags/{tag}', [TagController::class, 'destroy']);
    });

    // Article routes (accessible by super_admin, editor, and reporter)
    Route::middleware('role:super_admin,editor,reporter')->prefix('articles')->group(function () {
        Route::get('/', [ArticleController::class, 'index']);
        Route::post('/', [ArticleController::class, 'store']);
        Route::get('/{article}', [ArticleController::class, 'show']);
        Route::put('/{article}', [ArticleController::class, 'update']);
        Route::delete('/{article}', [ArticleController::class, 'destroy']);
        Route::post('/bulk-delete', [ArticleController::class, 'bulkDelete']);
        Route::post('/upload-image', [ArticleController::class, 'uploadImage']);
        Route::get('/preview/{slug}', [ArticleController::class, 'preview']);
        Route::put('/{article}/publish', [ArticleController::class, 'publish']);
        Route::put('/{article}/schedule', [ArticleController::class, 'schedule']);
    });

    // Reporter routes
    Route::middleware('role:reporter')->prefix('reporter')->group(function () {
        // Add reporter specific routes here
    });
});
