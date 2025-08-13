<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\Utility;
use App\EmpChangeTime;

class UpdateAttendanceLogs extends Command
{
    protected $signature = 'command:UpdateAttendanceLogs';
    protected $description = 'Command Description';

    public function __construct()
    {
        parent::__construct();
    } 

    public function handle()
    {
        $util = new Utility();

        $timeChangeRequest = EmpChangeTime::where('status', 0)->count();
        if($timeChangeRequest)
            $util->setAttendanceTime(); 
        else
            $util->updateAttendanceLogs(); 
    }
}