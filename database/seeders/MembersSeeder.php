<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MembersSeeder extends Seeder {

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {
    DB::table('members')->insert([
      'username' => 'setaloro',
      'email' => 'setaloro@gmail.com',
      'password' => 'setaloro',
      'sent_mails_this_month' => 0,
      'sent_mails_total' => 0,

    ]);
    DB::table('members')->insert([
      'username' => 'dni',
      'email' => 'dni.nicastro@gmail.com',
      'password' => 'dni',
      'sent_mails_this_month' => 0,
      'sent_mails_total' => 0,
    ]);
  }

}
