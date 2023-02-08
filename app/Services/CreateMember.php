<?php

namespace App\Services;

use App\Models\Member;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use TaylorNetwork\UsernameGenerator\Facades\UsernameGenerator;

class CreateMember {

  /**
   * Creates a new member given data from endpoint.
   *
   * @param  array  $data
   *
   * @return \App\Models\Member|bool
   * Member entity if successful,other the false
   */
  public function createMember(array $data): Member|bool {
    try {
      $credentials = $this->getCredentials();
      /** @var \App\Models\Member $member */
      $member = Member::create([
        'status' => 1,
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

      // Create file.
      $fileStorage = Storage::disk('private');
      $username = $member->getAttributeValue('username');
      $content = $this->createTextContent($username, $member->getAttributeValue('username'));
      $fileStorage->put("$username/credentials.txt", $content);

      return $member;
    }
    catch (\Exception $e) {
      Log::error($e->getMessage());
      return FALSE;
    }

  }

  /**
   * Creates the text file to send.
   *
   * @param  string  $username
   * @param  string  $password
   *
   * @return string
   * The content.
   */
  protected function createTextContent(string $username, string $password): string {
    $text = MailgunSendMail::BAIT_WEBSITE . "\n\n";
    $text .= "username:$username\n";
    $text .= "password:$password\n";

    return $text;
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