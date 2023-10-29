<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    HomeController,
    AuthController,
    CategoryController,
    ItemController
};

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

/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::get('/', [HomeController::class, 'index']);

Route::group(['prefix' => 'auth'], function ($router) {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth:sanctum')->group(function ($router) {
    Route::group(['prefix' => 'auth'], function ($router) {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::get('/user', [AuthController::class, 'userProfile']);
    });

    Route::group(['prefix' => 'categories'], function ($router) {
        Route::get('/', [CategoryController::class, 'getAll']);
    });

    
    Route::group(['prefix' => 'items'], function ($router) {
        Route::get('/', [ItemController::class, 'getAll']);
        Route::get('/{id}', [ItemController::class, 'get']);
        Route::post('/', [ItemController::class, 'add']);
        Route::put('/{id}', [ItemController::class, 'edit']);
        Route::delete('/{id}', [ItemController::class, 'delete']);
    });
});
