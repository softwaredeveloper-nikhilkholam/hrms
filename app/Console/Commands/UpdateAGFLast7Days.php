<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\Utility;

class UpdateAGFLast7Days extends Command
{
    protected $signature = 'command:UpdateAGFLast7Days';
    protected $description = 'Command Description UpdateAGFLast7Days';

    public function __construct()
    {
        parent::__construct();
    } 

    public function handle()
    {
        $util = new Utility();
        $util->updateAGFLast7Days(); 
    }
}


