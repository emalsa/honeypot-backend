<?php

namespace App\Services;

use App\Models\Spam;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SpamReceiver {

  /**
   * Store potential spammer
   */
  public static function handle(Request $request): JsonResponse {
    try {
      Spam::create([
        'firstname' => $request->get('firstname'),
        'lastname' => $request->get('lastname'),
        'email' => $request->get('email'),
        'message' => $request->get('message'),
      ]);
      return response()->json(['status' => 'ok']);
    }
    catch (\Exception $e) {
      Log::error($e->getMessage());
      return response()->json(['status' => 'error']);
    }

  }

}