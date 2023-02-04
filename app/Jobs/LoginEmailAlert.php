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

class LoginEmailAlert implements ShouldQueue {

  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * @var \App\Models\Member
   */
  protected Member $member;

  /**
   * @var \App\Services\MailgunSendMail
   */
  protected MailgunSendMail $mailgunSendMail;

  protected array $data;


  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct(Member $member, array $data) {
    $this->member = $member;
    $this->data = $data;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle(MailgunSendMail $mailgunSendMail) {
    try {
      $data = [
        'to' => $this->member->getAttribute('email'),
        'variable' => [
          'test1' => 'test',
          'just' => 'wow',
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
