<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;

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

Route::post('brain/getJWT', [ApiController::class, 'brainAuthenticate']); //login for admin
Route::group(['middleware' => ['jwt.verify']], function() {
    Route::post('brain/logout', [ApiController::class, 'brainLogout']);
    Route::get('brain/getAllClients', [ClientsController::class, 'brainAllClients']);
});

Route::post('user/getJWT', [ApiController::class, 'authenticate']);
Route::post('user/get', [UserController::class, 'index']);


Route::post('login', [ApiController::class, 'authenticate']);
Route::post('register', [ApiController::class, 'register']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('logout', [ApiController::class, 'logout']);
    Route::get('get_user', [ApiController::class, 'get_user']);
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{id}', [ProductController::class, 'show']);
    Route::post('create', [ProductController::class, 'store']);
    Route::put('update/{product}',  [ProductController::class, 'update']);
    Route::delete('delete/{product}',  [ProductController::class, 'destroy']);
});
