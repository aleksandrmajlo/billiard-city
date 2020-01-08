<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Table;
use Carbon\Carbon;

class UpdateTableprice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'table:price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Min price for Table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $timezone = 'Europe/Kiev';
        $todayStart = Carbon::parse('today 10:00', $timezone);
        $todayEnd = Carbon::parse('today 23:40', $timezone);
        if(Carbon::now()->between($todayStart, $todayEnd, false)){
            //это белый день

        }else{
            // это нахуй ночь

        }
    }
}
