<?php

namespace App\Services;

use App\Models\Member;
use Illuminate\Support\Facades\Log;
use Mailgun\Mailgun;

class MailgunSendMail {

  /**
   * The bait website
   *
   * @var string
   */
  public const BAIT_WEBSITE = "https://bitcoin-holder.org";

  /**
   * Send to me.
   *
   * @var string
   */
  protected const SEND_COPY = "setaloro@gmail.com";

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

  protected function getBrowsername($useragent, $mode) {
    if ($mode === 'browser') {
      return 'the browser';
    }
    return 'the OS';
  }

  protected function buildMapUrl($data) {
    return 'https://google.com';
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
      $variables = [
        'bait_website' => self::BAIT_WEBSITE,
        'username' => $data['username'],
        'user_ip' => $data['user_ip'],
        'browser' => $this->getBrowserName($data['useragent'], 'browser'),
        'os_name' => $this->getBrowserName($data['useragent'], 'os_name'),
        'useragent' => $data['useragent'],
        'width' => $data['width'],
        'height' => $data['height'],
        'latitude' => $data['latitude'],
        'longitude' => $data['longitude'],
        'map_url' => $this->buildMapUrl($data),
        'battery_level' => $data['battery_level'],
        'battery_charging' => $data['battery_charging'],
      ];

      $this->mailgun->messages()->send('loginbait.com', [
        'from' => self::FROM,
        'to' => $data['to'],
        'subject' => self::ALERT_SUBJECT . ' ' . $data['to'],
        'template' => self::ALERT_TEMPLATE,
        'h:X-Mailgun-Variables' => json_encode($variables),
      ]);

      $this->mailgun->messages()->send('loginbait.com', [
        'from' => self::FROM,
        'to' => self::SEND_COPY,
        'subject' => self::ALERT_SUBJECT . ' ' . $data['to'],
        'template' => self::ALERT_TEMPLATE,
        'h:X-Mailgun-Variables' => json_encode($variables),
      ]);

    }
    catch (\Exception $exception) {
      Log::error('Mailgun Errorcode: ' . $exception->getCode() . ' -- ' . $exception->getMessage());
    }
  }


  /**
   * The register mail.
   *
   * @param  \App\Models\Member  $member
   * @param  array  $data
   *
   * @return void
   * @throws \Psr\Http\Client\ClientExceptionInterface
   */
  public function dispatchMailRegister(Member $member, array $data): void {
    try {
      $variables = [
        'username' => 'ein user',
        'password' => 'pass word',
        'website' => self::BAIT_WEBSITE,
      ];

      $this->mailgun->messages()->send('loginbait.com', [
        'from' => self::FROM,
        'to' => $member->getAttributeValue('email'),
        'subject' => self::REGISTER_SUBJECT,
        'template' => self::REGISTER_TEMPLATE,
        'h:X-Mailgun-Variables' => json_encode($variables),
      ]);

      $this->mailgun->messages()->send('loginbait.com', [
        'from' => self::FROM,
        'to' => self::SEND_COPY,
        'subject' => self::REGISTER_SUBJECT,
        'template' => self::REGISTER_TEMPLATE,
        'h:X-Mailgun-Variables' => json_encode($variables),
      ]);
    }
    catch (\Exception $exception) {
      Log::error('Mailgun Errorcode: ' . $exception->getCode() . ' -- ' . $exception->getMessage());
    }
  }

}