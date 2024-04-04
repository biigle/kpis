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
        $nbrUser = User::whereBetween('login_at', [
            Carbon::now()->subMonth()->startOfMonth(),
            Carbon::now()->startOfMonth(),
        ])->count();

        DB::table('kpis_unique_users')->insert([
            'date' => Carbon::now()->subMonth()->endOfMonth(),
            'value' => $nbrUser,
        ]);
    }
}
