<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
  return view('welcome');
});

// Run queue worker, because there are some problems
// on the hoster side to run the queue worker via cron.
Route::get('/queue-work', function () {
  try {
    Log::error('Artisan called: "queue:work"');
    Artisan::call('queue:work --stop-when-empty');
  }
  catch (\Exception $e) {
    Log::error('Error calling /dispatch route.');
    Log::error($e->getMessage());
  }
});

// Stripe webhook.
Route::stripeWebhooks('stripe-webhook');
