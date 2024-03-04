<?php

namespace Biigle\Tests\Modules\Kpis;

use Biigle\Modules\Kpis\KpisServiceProvider;
use TestCase;

class KpisServiceProviderTest extends TestCase
{
    public function testServiceProvider()
    {
        $this->assertTrue(class_exists(KpisServiceProvider::class));
    }
}
