<?php

namespace Biigle\Tests\Modules\Kpis\Console\Commands;

use ApiTestCase;
use Carbon\Carbon;
use Biigle\Tests\UserTest;

class UserControllerTest extends ApiTestCase
{

    public function testGetUser(){

        $now = Carbon::now();
        $this->doTestApiRoute('GET', '/api/v1/kpis/user/'.$now->year.'/'.$now->month);

        $first = Carbon::now()->firstOfMonth()->toDateString();
        $last = Carbon::now()->endOfMonth()->toDateString();

        $user = UserTest::create(['login_at' => $first]);
        $this->artisan('kpis:count-user')->assertExitCode(0);

        $user->login_at = Carbon::now()->toDateString();
        $this->artisan('kpis:count-user')->assertExitCode(0);

        $user->login_at = $last;
        $this->artisan('kpis:count-user')->assertExitCode(0);

        $this->beAdmin(); //TODO: add check for user role
        $response = $this->get('/api/v1/kpis/user/'.$now->year.'/'.$now->month);
        $count = $response->getContent();
        $response->assertStatus(200);

        $this->assertEquals(3, $count);
    }

    public function testGetUniqueUser(){

        $now = Carbon::now();
        $this->doTestApiRoute('GET', '/api/v1/kpis/uuser/'.$now->year.'/'.$now->month);

        $first = Carbon::now()->firstOfMonth()->toDateString();
        $last = Carbon::now()->endOfMonth()->toDateString();

        UserTest::create(['login_at' => $first]);
        UserTest::create(['login_at' => $first]);

        // set time on first of next month
        Carbon::setTestNow(Carbon::now()->addMonth()->firstOfMonth());

        $this->artisan('kpis:count-unique-user')->assertExitCode(0);

        $this->beAdmin(); //TODO: add check for user role
        $response = $this->get('/api/v1/kpis/uuser/'.$now->year.'/'.$now->month);
        $count = $response->getContent();
        $response->assertStatus(200);

        $this->assertEquals(2, $count);
    }
}