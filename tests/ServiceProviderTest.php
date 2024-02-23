<?php

namespace Tests;

use FullStackAppCo\ReactMake\ServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase;

class ServiceProviderTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }

    public function test_it_publishes_stubs()
    {
        File::deleteDirectory(base_path('stubs'));
        File::deleteDirectory(resource_path('js/components'));

        $result = Artisan::call('vendor:publish', ['--tag' => 'react-stub']);

        $this->assertSame(0, $result);

        foreach (ServiceProvider::stubs() as $stub) {
            $this->assertTrue(File::exists(base_path("stubs/{$stub}")), "Stub {$stub} was not published.");
        }
    }
}