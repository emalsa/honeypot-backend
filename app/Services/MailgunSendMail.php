<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Mailgun\Mailgun;

class MailgunSendMail {

  /**
   * Send to me.
   *
   * @var string
   */
  protected const SEND_ALSO = "setaloro@gmail.com";

  /**
   * From.
   *
   * @var string
   */
  protected const FROM = 'Loginbait.com noreply@loginbait.com';

  /**
   * Alert: Subject.
   *
   * @var string
   */
  protected const ALERT_SUBJECT = 'Login attempt detected for';

  /**
   * Alert: Template.
   *
   * @var string
   */
  protected const ALERT_TEMPLATE = 'alert__login_attempt';

  /**
   * Register: Subject.
   *
   * @var string
   */
  protected const REGISTER_SUBJECT = 'Registration for loginbait.com';

  /**
   * Register: Template.
   *
   * @var string
   */
  protected const REGISTER_TEMPLATE = 'register__first_mail';


  /**
   * The mailgun send service.
   *
   * @var \Mailgun\Mailgun
   */
  protected Mailgun $mailgun;

  /**
   * The construct.
   */
  public function __construct() {
    $this->mailgun = Mailgun::create(env('MAILGUN_APIKEY'));
  }

  /**
   * The alert mail.
   *
   * @param  array  $data
   *
   * @return void
   * @throws \Psr\Http\Client\ClientExceptionInterface
   */
  public function dispatchMailAlert(array $data): void {
    try {
      $this->mailgun->messages()->send('loginbait.com', [
        'from' => self::FROM,
        'to' => $data['to'],
        'subject' => self::ALERT_SUBJECT . ' ' . $data['to'],
        // 'template' => self::ALERT_TEMPLATE,
        'template' => self::REGISTER_TEMPLATE,
        'h:X-Mailgun-Variables' => json_encode($data['variable']),
      ]);

      $this->mailgun->messages()->send('loginbait.com', [
        'from' => self::FROM,
        'to' => self::SEND_ALSO,
        'subject' => self::ALERT_SUBJECT . ' ' . $data['to'],
        // 'template' => self::ALERT_TEMPLATE,
        'template' => self::REGISTER_TEMPLATE,
        'h:X-Mailgun-Variables' => json_encode($data['variable']),
      ]);

    }
    catch (\Exception $exception) {
      Log::error('Mailgun Errorcode: ' . $exception->getCode() . ' -- ' . $exception->getMessage());
    }
  }


  /**
   * The register mail
   *
   * @param  bool  $memberCreated
   * @param  array  $data
   *
   * @return void
   */
  public function dispatchMailRegister(bool $memberCreated, array $data): void {
    try {
      if ($memberCreated) {

        $this->mailgun->messages()->send('loginbait.com', [
          'from' => self::FROM,
          'to' => $data['billing_details']['email'],
          'subject' => self::REGISTER_SUBJECT,
          'template' => self::REGISTER_TEMPLATE,
          'h:X-Mailgun-Variables' => '{"testname": "Jamie"}',
        ]);

        $this->mailgun->messages()->send('loginbait.com', [
          'from' => self::FROM,
          'to' => self::SEND_ALSO,
          'subject' => self::REGISTER_SUBJECT,
          'template' => self::REGISTER_TEMPLATE,
          'h:X-Mailgun-Variables' => '{"testname": "Jamie"}',
        ]);
      }
    }
    catch (\Exception $exception) {
      Log::error('Mailgun Errorcode: ' . $exception->getCode() . ' -- ' . $exception->getMessage());
    }
  }

}