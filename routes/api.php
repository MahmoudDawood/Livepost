<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix("v1")
    // Move routes to a v1 directory when new versions come
    ->group( function () {
    require __DIR__ . '/api/user.php';
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
