<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $earliest = DB::table('kpis_users')->min('date');

        if (!$earliest) {
            return;
        }

        DB::transaction(function () use ($earliest) {
            DB::table('kpis_users')->delete();

            $current = Carbon::parse($earliest)
                ->settings(['monthOverflow' => false])
                ->startOfMonth();
            $endDate = Carbon::now()
                ->settings(['monthOverflow' => false])
                ->startOfMonth();

            $rows = [];
            while ($current->lessThanOrEqualTo($endDate)) {
                $rows[] = [
                    'date' => $current,
                    'value' => DB::table('users')->where('created_at', '<', $current)->count(),
                ];
                $current->addMonth();
            }

            DB::table('kpis_users')->insert($rows);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // can't be reverted
    }
};
