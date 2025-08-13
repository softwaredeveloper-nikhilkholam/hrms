<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\Utility;
use App\EmpDet;

class UpdateHolidays extends Command
{
    protected $signature = 'command:UpdateHolidays';
    protected $description = 'Add Current month all holidays in holiday list (Sunday and saturday)';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        EmpDet::where('active', 1)->update(['attendanceStatus'=>0]);
    }
}

        