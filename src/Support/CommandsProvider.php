<?php

namespace _77Gears_\ReactMake\Support;

use Illuminate\Support\ServiceProvider;
use _77Gears_\ReactMake\Console\Commands\ReactComponentCommand;

class CommandsProvider extends ServiceProvider
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
            ReactComponentCommand::class,
        ]);

        $publishes = [];

        foreach (static::stubs() as $stub) {
            $publishes[__DIR__ . "/../../stubs/{$stub}"] = base_path("stubs/{$stub}");
        }

        $this->publishes($publishes, 'react-stub');
    }
}