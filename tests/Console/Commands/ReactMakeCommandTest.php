<?php

namespace Tests\Console\Commands;

use FullStackAppCo\ReactMake\ServiceProvider;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;
use Symfony\Component\Console\Exception\RuntimeException;

class ReactMakeCommandTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }

    public function test_it_requires_name_parameter()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('missing: "name"');
        $this->artisan('make:react');
    }

    public function test_it_checks_if_component_already_exists()
    {
        $this->mock(Filesystem::class, function (MockInterface $mock) {
            $filepath = resource_path('js/components/TestComponent.jsx');

            // Stubs.
            $mock->allows([
                'isDirectory' => true,
                'get' => 'template content',
                'put' => 23,
            ]);

            // Behaviour under test.
            $mock->shouldReceive('exists')
                ->with($filepath)
                ->once()
                ->andReturn(false);

            // The method is subsequently called
            // but this is not the call under test.
            $mock->shouldReceive('exists')->once();
        });

        $result = Artisan::call('make:react', ['name' => 'TestComponent']);
        $this->assertSame(0, $result);
    }

    public function test_it_creates_directories()
    {
        $this->mock(Filesystem::class, function (MockInterface $mock) {
            // Stubs.
            $mock->allows([
                'exists' => false,
                'isDirectory' => false,
                'get' => 'template content',
                'put' => 23,
            ]);

            $dirpath = resource_path('js/components/foo/bar');

            $mock->shouldReceive('makeDirectory')
                ->withArgs([$dirpath, 0777, true, true])
                ->once()
                ->andReturn(true);
        });

        $this
            ->artisan('make:react', ['name' => 'foo/bar/TestComponent'])
            ->expectsOutput('TestComponent.jsx created')
            ->assertSuccessful();
    }

    public function test_it_uses_prefix_config()
    {
        Config::set('react.prefix', 'Components');

        $this->mock(Filesystem::class, function (MockInterface $mock) {
            // Stubs.
            $mock->allows([
                'exists' => false,
                'isDirectory' => false,
                'get' => 'template content',
                'put' => 23,
            ]);

            $dirpath = resource_path('js/Components/foo/bar');

            $mock->shouldReceive('makeDirectory')
                ->withArgs([$dirpath, 0777, true, true])
                ->once()
                ->andReturn(true);
        });

        $this
            ->artisan('make:react', ['name' => 'foo/bar/TestComponent'])
            ->expectsOutput('TestComponent.jsx created')
            ->assertSuccessful();
    }

    public function test_it_retrieves_stub()
    {
        $this->mock(Filesystem::class, function (MockInterface $mock) {
            // Stubs.
            $mock->allows([
                'exists' => false,
                'isDirectory' => true,
                'put' => 890,
            ]);

            $mock->shouldReceive('get')
                ->with(realpath(__DIR__ . '/../../../stubs/react.stub'))
                ->once();
        });

        $this
            ->artisan('make:react', ['name' => 'TestComponent'])
            ->expectsOutput('TestComponent.jsx created')
            ->assertSuccessful();
    }

    public function test_it_writes_component()
    {
        $this->mock(Filesystem::class, function (MockInterface $mock) {
            // Stubs.
            $mock->allows([
                'exists' => false,
                'isDirectory' => true,
                'get' => 'template content',
            ]);

            $mock->shouldReceive('put')
                ->withArgs([resource_path('js/components/TestComponent.jsx'), 'template content'])
                ->once();
        });

        $this
            ->artisan('make:react', ['name' => 'TestComponent'])
            ->expectsOutput('TestComponent.jsx created')
            ->assertSuccessful();
    }

    public function test_it_uses_provided_extension()
    {
        $this->mock(Filesystem::class, function (MockInterface $mock) {
            // Stubs.
            $mock->allows([
                'exists' => false,
                'isDirectory' => true,
                'get' => 'template content',
            ]);

            $mock->shouldReceive('put')
                ->withArgs([resource_path('js/components/TestComponent.whatever'), 'template content'])
                ->once();
        });

        $this
            ->artisan('make:react', ['name' => 'TestComponent', '--extension' => 'whatever'])
            ->expectsOutput('TestComponent.whatever created')
            ->assertSuccessful();
    }

    public function test_it_uses_overridden_stubs()
    {
        File::deleteDirectory(base_path('stubs'));
        File::deleteDirectory(resource_path('js/components'));

        $stubPath = base_path('stubs/react.stub');
        File::makeDirectory(dirname($stubPath));
        File::put($stubPath, 'Overridden stub');

        $this
            ->artisan('make:react', ['name' => 'TestComponent'])
            ->expectsOutput('TestComponent.jsx created')
            ->assertSuccessful();

        $this->assertSame('Overridden stub', File::get(resource_path('js/components/TestComponent.jsx')));
    }

    public function test_it_correctly_replaces_dummy_component()
    {
        $this->mock(Filesystem::class, function (MockInterface $mock) {
            // Stubs.
            $mock->allows([
                'exists' => false,
                'isDirectory' => true,
                'get' => 'DummyComponent',
            ]);

            $mock->shouldReceive('put')
                ->withArgs([resource_path('js/components/sub/dir/TestComponent.jsx'), 'TestComponent'])
                ->once();
        });

        $this
            ->artisan('make:react', ['name' => 'sub/dir/TestComponent'])
            ->expectsOutput('TestComponent.jsx created')
            ->assertSuccessful();
    }

    public function test_it_supports_typescript()
    {
        $this->mock(Filesystem::class, function (MockInterface $mock) {
            // Stubs.
            $mock->allows([
                'exists' => false,
                'isDirectory' => true,
            ]);
            $mock->shouldReceive('get')
                ->once()
                ->with(realpath(__DIR__ . '/../../../stubs/react.ts.stub'))
                ->andReturn('template content');
            $mock->shouldReceive('put')
                ->withArgs([resource_path('js/components/TestComponent.tsx'), 'template content'])
                ->once();
        });

        $this
            ->artisan('make:react', ['name' => 'TestComponent', '--typescript' => true])
            ->expectsOutput('TestComponent.tsx created')
            ->assertSuccessful();
    }

    public function test_absolute_path()
    {
        $this->mock(Filesystem::class, function (MockInterface $mock) {
            // Stubs.
            $mock->allows([
                'exists' => false,
                'isDirectory' => true,
            ]);
            $mock->shouldReceive('get')
                ->once()
                ->with(realpath(__DIR__ . '/../../../stubs/react.stub'))
                ->andReturn('template content');
            $mock->shouldReceive('put')
                ->withArgs([resource_path('js/pages/TestPage.jsx'), 'template content'])
                ->once();
        });

        $this
            ->artisan('make:react', ['name' => '/pages/TestPage'])
            ->expectsOutput('TestPage.jsx created')
            ->assertSuccessful();
    }

    public function test_it_uses_configured_default_options()
    {
        Config::set('react.defaults', ['typescript' => true, 'extension' => 'ts']);

        $this->mock(Filesystem::class, function (MockInterface $mock) {
            // Stubs.
            $mock->allows([
                'exists' => false,
                'isDirectory' => true,
            ]);
            $mock->shouldReceive('get')
                ->once()
                ->with(realpath(__DIR__ . '/../../../stubs/react.ts.stub'))
                ->andReturn('template content');
            $mock->shouldReceive('put')
                ->withArgs([resource_path('js/components/TestComponent.ts'), 'template content'])
                ->once();
        });

        $this
            ->artisan('make:react', ['name' => 'TestComponent'])
            ->expectsOutput('TestComponent.ts created')
            ->assertSuccessful();
    }

    public function test_cli_extension_option_overrides_configured_default()
    {
        Config::set('react.defaults', ['typescript' => true, 'extension' => 'ts']);

        $this->mock(Filesystem::class, function (MockInterface $mock) {
            // Stubs.
            $mock->allows([
                'exists' => false,
                'isDirectory' => true,
            ]);
            $mock->shouldReceive('get')
                ->once()
                ->with(realpath(__DIR__ . '/../../../stubs/react.ts.stub'))
                ->andReturn('template content');
            $mock->shouldReceive('put')
                ->withArgs([resource_path('js/components/TestComponent.blaarg'), 'template content'])
                ->once();
        });

        $this
            ->artisan('make:react', ['name' => 'TestComponent', '--extension' => 'blaarg'])
            ->expectsOutput('TestComponent.blaarg created')
            ->assertSuccessful();
    }

    public function test_it_uses_configured_base()
    {
        Config::set('react.base', resource_path('ts'));

        $this->mock(Filesystem::class, function (MockInterface $mock) {
            // Stubs.
            $mock->allows([
                'exists' => false,
                'isDirectory' => true,
            ]);
            $mock->shouldReceive('get')
                ->once()
                ->with(realpath(__DIR__ . '/../../../stubs/react.ts.stub'))
                ->andReturn('template content');
            $mock->shouldReceive('put')
                ->withArgs([resource_path('ts/components/TestComponent.tsx'), 'template content'])
                ->once();
        });

        $this
            ->artisan('make:react', ['name' => 'TestComponent', '--typescript' => 'true'])
            ->expectsOutput('TestComponent.tsx created')
            ->assertSuccessful();
    }
}
