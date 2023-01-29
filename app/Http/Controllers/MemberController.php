<?php

namespace App\Http\Controllers;

use App\Models\Member;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class MemberController extends Controller {

  /**
   * Method from Honeypot, sends the alert email if username and password matches.
   *
   * @param  \Illuminate\Http\Request  $request
   * The request.
   *
   * @return \Illuminate\Http\JsonResponse
   * The Json Response.
   */
  public function getMember(Request $request) {
    if (1) {
      return response()->json(['status' => 'ok', 'redirect' => '/dashboard']);
      // Send email
    }
    else {
      // Error
      return response()->json(['status' => 'error', 'message' => 'Username or password incorrect']);
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
