<?php

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
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
Route::get('users', function () {
    return new JsonResource([
        "data" => "users"
    ]);
});

Route::get('users/{user}', function ($user) {
    return new JsonResource([
        "data" => $user
    ]);
});


Route::post('users', function (Request $request) {
    return new JsonResource([
        "data" => "posted"
    ]);
});

Route::patch('users/{id}', function ($id) {
    return new JsonResource([
        "data" => "User ".$id." patched"
    ]);
});

Route::delete('users/{id}', function ($id) {
    return new JsonResource([
        "data" => "User ".$id." deleted"
    ]);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
