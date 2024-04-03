<?php

namespace Biigle\Modules\Kpis\Console\Commands;

use Biigle\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CountUser extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'kpis:count-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Counts active users of the previous day';

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $yesterday = Carbon::yesterday()->toDateString();
        $nbrUser = User::where('login_at', '=', $yesterday)->count();

        DB::table('kpis_users')->insert(['date' => $yesterday, 'value' => $nbrUser]);
    }
}
