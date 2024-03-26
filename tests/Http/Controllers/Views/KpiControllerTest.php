<?php

namespace Biigle\Modules\Kpis\Tests\Http\Controllers\Views;

use ApiTestCase;

class KpiControllerTest extends ApiTestCase
{
    public function testShow(){

        $kpiPath = "admin/kpis/5";

        $this->beUser();
        $response = $this->get($kpiPath)->assertForbidden();

        $this->beGuest();
        $response = $this->get($kpiPath)->assertForbidden();

        $this->beEditor();
        $response = $this->get($kpiPath)->assertForbidden();

        $this->beExpert();
        $response = $this->get($kpiPath)->assertForbidden();

        $this->beAdmin();
        $response = $this->get($kpiPath)->assertForbidden();

        $this->beGlobalReviewer();
        $response = $this->get($kpiPath);
        $response->assertStatus(200);

        $this->beGlobalAdmin();
        $response = $this->get($kpiPath);
        $response->assertStatus(200);

        // Indices > 5 are set to 5 (last month index)
        $kpiPath = "admin/kpis/50";
        $this->beGlobalAdmin();
        $response = $this->get($kpiPath);
        $response->assertStatus(200);
    }
}