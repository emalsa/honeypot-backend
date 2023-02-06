<?php

namespace App\Http\Controllers;

use App\Jobs\LoginEmailAlertJob;
use App\Models\Member;
use App\Services\MailgunSendMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MemberController extends Controller {

  /**
   * @var \App\Services\MailgunSendMail
   */
  private MailgunSendMail $mailgunSendMail;

  public function __construct(MailgunSendMail $mailgunSendMail) {
    $this->mailgunSendMail = $mailgunSendMail;

  }

  /**
   * Method from Honeypot, sends the alert email if username and password matches.
   *
   * @param  \Illuminate\Http\Request  $request
   * The request.
   *
   * @return \Illuminate\Http\JsonResponse
   * The Json Response.
   */
  public function getMember(Request $request): JsonResponse {
    try {
      $username = $request->get('username');
      $password = $request->get('password');
      /** @var \Illuminate\Database\Eloquent\Collection $members */
      $members = Member::where([
        ['username', '=', $username],
        ['password', '=', $password],
      ])->first();


      if ($members instanceof Member) {
        $capturedData = $request->get('data');
        if (!is_array($capturedDataDecoded = json_decode($capturedData, TRUE))) {
          $capturedDataDecoded = [];
        }
        // Add to queue.
        LoginEmailAlertJob::dispatch($members, $capturedDataDecoded);

        return response()->json(['status' => 'ok', 'redirect' => '/dashboard']);
      }

      return response()->json(['status' => 'error', 'message' => 'Username or password incorrect.']);
    }
    catch (\Error|\Exception $exception) {
      return response()->json(['status' => 'error', 'message' => 'Something went wrong.']);
    }

  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    die('dd');
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create() {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   *
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request) {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Member  $member
   *
   * @return \Illuminate\Http\Response
   */
  public function show(Member $member) {
    return $member->getAttribute('username');
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Member  $member
   *
   * @return \Illuminate\Http\Response
   */
  public function edit(Member $member) {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Member  $member
   *
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Member $member) {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Member  $member
   *
   * @return \Illuminate\Http\Response
   */
  public function destroy(Member $member) {
    //
  }

}
