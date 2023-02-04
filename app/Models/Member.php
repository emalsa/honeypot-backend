<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use TaylorNetwork\UsernameGenerator\FindSimilarUsernames;

class Member extends Model {

  use HasFactory, FindSimilarUsernames;

  protected $fillable = [
    'username',
    'password',
    'email',
    'name',
    'city',
    'country',
    'sent_mails_this_month',
    'sent_mails_total',
    'expires',
  ];

}
