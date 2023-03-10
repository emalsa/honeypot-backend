<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});

// CRUD.
Route::resource('members', \App\Http\Controllers\MemberController::class);

// Endpoint for frontend "log in".
Route::post('get-member', 'App\Http\Controllers\MemberController@getMember');
Route::post('spam', 'App\Services\SpamReceiver@handle');
