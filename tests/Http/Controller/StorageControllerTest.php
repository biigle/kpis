<?php

namespace Biigle\Tests\Modules\Kpis\Console\Commands;

use ApiTestCase;
use Carbon\Carbon;
use Biigle\Tests\ImageTest;
use Biigle\Tests\VideoTest;

class StorageControllerTest extends ApiTestCase
{
    public function testGetStorageUsage()
    {
        $now = Carbon::now();
        $this->doTestApiRoute('GET', '/api/v1/kpis/storage/'.$now->year.'/'.$now->month);

        ImageTest::create([
            'attrs' => [
                'size' => 3189539,
                'mimetype' => 'image\/jpeg',
                'width' => 4024,
                'height' => 6048
            ]
        ]);
        VideoTest::create([
            'attrs' => [
                'size' => 3189539,
                'mimetype' => 'video/mp4',
                'width' => 4024,
                'height' => 6048
            ]
        ]);

        // set time on first of next month
        Carbon::setTestNow(Carbon::now()->addMonth()->firstOfMonth());

        $this->artisan('kpis:determine-storage-usage')->assertExitCode(0);

        $this->beAdmin(); //TODO: add check for user role
        $response = $this->get('/api/v1/kpis/storage/'.$now->year.'/'.$now->month);
        $count = $response->getContent();
        $response->assertStatus(200);

        $this->assertEquals(8, $count);
    }

    public function testNoStorageFound(){
        $now = Carbon::now();
        $this->artisan('kpis:determine-storage-usage')->assertExitCode(0);
        $this->beAdmin(); //TODO: add check for user role
        $response = $this->get('/api/v1/kpis/storage/'.$now->year.'/'.$now->month);
        $count = $response->getContent();
        $response->assertStatus(200);

        $this->assertEquals(0, $count);
    }
}
