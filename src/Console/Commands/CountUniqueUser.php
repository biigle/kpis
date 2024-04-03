<?php

namespace Biigle\Modules\Kpis\Console\Commands;

use Biigle\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CountUniqueUser extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'kpis:count-unique-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Counts unique user of last month';

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $nbrUser = $this->countUniqueUser();

        $date = Carbon::now()->subMonth()->endOfMonth()->toDateString();

        DB::table('kpis_unique_users')->insert(['date' => $date, 'value' => $nbrUser]);
    }

    private function countUniqueUser()
    {
        $fromDate = Carbon::now()->subMonth()->startOfMonth()->toDateString();
        $tillDate = Carbon::now()->subMonth()->endOfMonth()->toDateString();
        return User::whereBetween('login_at', [$fromDate, $tillDate])->count();
    }
}
