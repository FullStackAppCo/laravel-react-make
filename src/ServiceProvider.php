<?php

namespace FullStackAppCo\ReactMake;

use Illuminate\Support\ServiceProvider as BaseProvider;
use FullStackAppCo\ReactMake\Console\Commands\ReactMakeCommand;

class ServiceProvider extends BaseProvider
{

    public static function stubs()
    {
        return [
            'react.stub',
            'react-class.stub',
        ];
    }

    public function boot()
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            ReactMakeCommand::class,
        ]);

        $publishes = [];

        foreach (static::stubs() as $stub) {
            $publishes[__DIR__ . "/../stubs/{$stub}"] = base_path("stubs/{$stub}");
        }

        $this->publishes($publishes, 'react-stub');
    }
}