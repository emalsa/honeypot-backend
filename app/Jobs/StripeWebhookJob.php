<?php

namespace App\Jobs;

use App\Models\Member;
use App\Services\CreateMember;
use App\Services\MailgunSendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Spatie\WebhookClient\Models\WebhookCall;

class StripeWebhookJob implements ShouldQueue {

  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * The stripe webhook library.
   *
   * @var \Spatie\WebhookClient\Models\WebhookCall
   */
  protected WebhookCall $webhookCall;

  /**
   * The mailgun service.
   *
   * @var \App\Services\MailgunSendMail
   */
  protected MailgunSendMail $mailgunSendMail;

  /**
   * The create member service.
   *
   * @var \App\Services\CreateMember
   */
  protected CreateMember $createMember;

  /**
   * Create a new job instance.
   *
   * @param  \Spatie\WebhookClient\Models\WebhookCall  $webhookCall
   * The webhook call.
   */
  public function __construct(WebhookCall $webhookCall) {
    $this->webhookCall = $webhookCall;
  }

  /**
   * Executes the job.
   *
   * @param  \App\Services\CreateMember  $createMember
   * The create member service.
   * @param  \App\Services\MailgunSendMail  $mailgunSendMail
   * The Mailgun service to send email.
   *
   * @return void
   */
  public function handle(CreateMember $createMember, MailgunSendMail $mailgunSendMail): void {
    $this->mailgunSendMail = $mailgunSendMail;
    $this->createMember = $createMember;
    try {
      $type = $this->webhookCall->payload['type'];

      switch ($type) {
        case 'charge.succeeded':
          $data = $this->webhookCall->payload['data']['object'];
          $member = $this->createMember->createMember($data);
          if ($member instanceof Member) {
            $this->mailgunSendMail->dispatchMailRegister($member, $data);
          }
          // Error handling?
          break;
        default:
          return;
      }

    }
    catch (\Exception $exception) {
      Log::error($exception->getMessage());
    }
  }

}
