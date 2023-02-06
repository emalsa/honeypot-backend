<?php

namespace App\Jobs;

use App\Models\Member;
use App\Services\MailgunSendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class LoginEmailAlertJob implements ShouldQueue {

  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * The member entity.
   *
   * @var \App\Models\Member
   */
  protected Member $member;

  /**
   * The data used in email alert.
   *
   * @var array
   */
  protected array $data;


  /**
   * The construct.
   *
   * @param  \App\Models\Member  $member
   * @param  array  $data
   */
  public function __construct(Member $member, array $data) {
    $this->member = $member;
    $this->data = $data;
    Log::error('LoginEmailAlertJob __construct');
  }

  /**
   * Executes the job.
   *
   * @param  \App\Services\MailgunSendMail  $mailgunSendMail
   *
   * @return void
   */
  public function handle(MailgunSendMail $mailgunSendMail) {
    Log::error('LoginEmailAlertJob handle');
    Log::debug(json_encode($this->data));

    try {
      $data = [
        'to' => $this->member->getAttribute('email'),
        'variable' => [
          'userip' => $this->data['userip'],
          'useragent' => $this->data['useragent'],
        ],
      ];
      $mailgunSendMail->dispatchMailAlert($data);
      $sentMailThisMonths = $this->member->getAttribute('sent_mails_this_month');
      $sent_mails_total = $this->member->getAttribute('sent_mails_total');

      $this->member->setAttribute('sent_mails_this_month', $sentMailThisMonths + 1);
      $this->member->setAttribute('sent_mails_total', $sent_mails_total + 1);
      $this->member->save();
    }
    catch (\Exception $exception) {
      Log::error($exception->getMessage());
    }

  }

}