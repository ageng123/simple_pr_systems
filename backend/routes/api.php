<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MasterData\{
    User
};
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(["prefix" => "users"], function(){
    Route::get("/", [User::class, "index"])->name("api.user.index");
    Route::post("/", [User::class, "store"])->name("api.user.store");
    Route::get("/{uuid}", [User::class, "find"])->name("api.user.find");
    Route::put("/{uuid}", [User::class, "update"])->name("api.user.update");
    Route::delete("/{uuid}", [User::class, "delete"])->name("api.user.delete");
});
Route::group(["prefix" => "auth"], function(){
    Route::post("authenticate", [User::class, "authenticate"])->name("api.auth.login");
});
