<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Laravel\Sanctum\Sanctum;
use App\Models\Tokens;
use DateTime;

class DeleteExpiredTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tokens:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now();

        $limit = $now->subHours(6);

        try{
            $delete = DB::table('personal_access_tokens')
                ->where('created_at', '<', $limit)
                ->delete();
        }
        catch (\Illuminate\Database\QueryException $e)
        {
            echo "error deleting!! ".$e;
        }

        echo "Deleted OK for today... ".date('Y-m-d')." , items deleted == ".$delete."<br>";
    }
}
