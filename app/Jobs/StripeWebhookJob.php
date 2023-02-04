<?php

namespace App\Jobs;

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

  /** @var \Spatie\WebhookClient\Models\WebhookCall */
  protected WebhookCall $webhookCall;

  /**
   * @var \App\Services\MailgunSendMail
   */
  protected MailgunSendMail $mailgunSendMail;

  /**
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
   * Execute the job.
   *
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
          $memberCreated = $this->createMember->createMember($data);
          $this->mailgunSendMail->dispatchMailRegister($memberCreated, $data);
          break;
        default:
          return;
      }

    }
    catch (\Exception $exception) {
      Log::error($exception->getMessage());
    }
    return;
  }

}
