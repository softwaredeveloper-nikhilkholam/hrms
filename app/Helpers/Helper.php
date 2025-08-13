<?php

namespace App\Helpers;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use DateTime;
use DateTimeZone;
use DB;
use Auth;
use Mail;

class Helper 
{
    public static function basic_email() {
      //   $data = array('name'=>"Nikhil Kholam");
     
      //   Mail::send(['text'=>'mail'], $data, function($message) {
      //      $message->to('nikhilkholam8668@gmail.com', 'Tutorials Point')->subject
      //         ('Laravel Basic Testing Mail');
      //      $message->from('awshrms2021@gmail.com','Nikhil Kholam');
      //   });
     }
}