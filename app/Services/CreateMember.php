<?php

namespace App\Services;

use App\Models\Member;
use Illuminate\Support\Facades\Log;
use TaylorNetwork\UsernameGenerator\Facades\UsernameGenerator;

class CreateMember {

  /**
   * Creates a new member given data from endpoint.
   *
   * @param  array  $data
   *
   * @return bool
   */
  public function createMember(array $data): bool {
    try {
      $credentials = $this->getCredentials();
      Member::create([
        'username' => $credentials['username'],
        'password' => $credentials['password'],
        'email' => $data['billing_details']['email'],
        'name' => $data['billing_details']['name'],
        'city' => $data['billing_details']['address']['city'],
        'country' => $data['billing_details']['address']['country'],
        'sent_mails_this_month' => 0,
        'sent_mails_total' => 0,
        'expires' => date('Y-m-d', strtotime("+1 year")),
      ]);

      return TRUE;
    }
    catch (\Exception $e) {
      Log::error($e->getMessage());
      return FALSE;
    }

  }

  /**
   * Generates a random username and password.
   *
   * @return array
   */
  protected function getCredentials(): array {
    return [
      'username' => UsernameGenerator::generate(),
      'password' => UsernameGenerator::generate() . rand(9, 150) . '=',
    ];
  }

}