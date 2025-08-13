<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\Utility;

class CheckHalfDayOrAbsentDay extends Command
{
    protected $signature = 'command:UpdateMismatchTime';
    protected $description = 'Command Description CheckHalfDayOrAbsentDay';

    public function __construct()
    {
        parent::__construct();
    } 

    public function handle()
    {
        $util = new Utility();
        $util->checkHalfDayOrAbsentDay(); 
    }
}