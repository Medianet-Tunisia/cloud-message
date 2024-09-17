<?php

namespace MedianetDev\CloudMessage\Tests;

use MedianetDev\CloudMessage\CloudMessageServiceProvider;
use Orchestra\Testbench\TestCase;

class ExampleTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [CloudMessageServiceProvider::class];
    }

    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
