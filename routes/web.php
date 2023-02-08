<?php

use App\Services\SpamReceiver;
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
    Artisan::call('queue:work --stop-when-empty --max-jobs=20');
  }
  catch (\Exception $e) {
    Log::error('Error calling /queue-work route.');
    Log::error($e->getMessage());
  }
});

// Stripe webhook.
Route::stripeWebhooks('stripe-webhook');
