<?php

namespace FullStackAppCo\ReactMake;

use FullStackAppCo\ReactMake\Console\Commands\ReactMakeCommand;
use Illuminate\Support\ServiceProvider as BaseProvider;

class ServiceProvider extends BaseProvider
{
    public static function stubs()
    {
        return [
            'react.stub',
        ];
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/react.php', 'react');
    }

    public function boot()
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            ReactMakeCommand::class,
        ]);

        $this->publishesStubs();
        $this->publishesConfig();
    }

    protected function publishesStubs(): void
    {
        $publishes = [];

        foreach (static::stubs() as $stub) {
            $publishes[__DIR__."/../stubs/{$stub}"] = base_path("stubs/{$stub}");
        }

        $this->publishes($publishes, 'react-stub');
    }

    protected function publishesConfig(): void
    {
        $this->publishes([__DIR__.'/../config/react.php' => config_path('react.php')], 'react-config');
    }
}
