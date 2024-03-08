<?php

use Illuminate\Http\Request;
use App\Helpers\Routes\RouteHelper;
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

Route::prefix('v1')
    // Move routes to a v1 directory when new versions come
    ->group(function () {
        RouteHelper::importRouteFiles(__DIR__ . '/api');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
