<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;

class Counter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:counter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add 15 to the counter every day';

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

        $counter = DB::table('counter')->find(1);
        $time = strtotime(substr($counter->updated_at,0,10));
        $current = strtotime(date('Y-m-d'));
        $hour = date('H:i');

        if(($current-$time)/86400 >= 1){
            if($hour == '08:00'){
                //每天08:00生成数组
                $max = 15;  //设置为可以被60整除的数
                $step = 12*60/$max;
                $cron = [];
                for ($i = 0; $i < $max; $i++){
                    $start = strtotime(date('Y-m-d').' 08:00:00')+$i*$step*60;
                    $random = mt_rand(1,$step);
                    $item = $start+$random*60;
                    $cron[] = date('H:i',$item);
                }

                DB::table('counter')->where('id', '=', 1)->update(['cron' => json_encode($cron), 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
            }
        }
        if($counter->cron!='') {
            $cron = json_decode($counter->cron, true);
            if (in_array($hour, $cron)) {
                DB::table('counter')->where('id', '=', 1)->update(['count' => $counter->count + 1, 'updated_at' => date('Y-m-d H:i:s')]);
            }
        }
        return true;
    }
}
