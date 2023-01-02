<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MasterData\{
    User,Role,Organization
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
Route::resource('role', Role::class);
Route::group(["prefix"=>"organization"], function(){
    Route::get("/tree/{id}", [Organization::class, 'tree'])->name('api.org.tree');
    Route::get("/", [Organization::class, 'index'])->name("api.org.get_all");
    Route::post("/", [Organization::class, 'store'])->name("api.org.store");
    Route::get("/{id}", [Organization::class, 'show'])->name("api.org.get");
    Route::put("/{id}", [Organization::class, 'update'])->name("api.org.update");
    Route::delete("/{id}", [Organization::class, 'delete'])->name("api.org.delete");
});
