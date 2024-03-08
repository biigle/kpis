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
    protected $description = 'Counts active user of today';

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $nbrUser = $this->countUser();

        DB::transaction(function () use ($nbrUser) {
            DB::table('kpis_users')->insert(['date' => Carbon::today()->toDateString(), 'value' => $nbrUser]);
        });
    }

    private function countUser()
    {
        $today = Carbon::now()->toDateString();

        return User::where('login_at', '=', $today)->get()->count();
    }
}
