<?php

namespace FullStackAppCo\ReactMake\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\App;

class ReactMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = <<<'TEXT'
make:react
    {name : The name of the React component}
    {--x|jsx : Use .jsx file extension (tsx when used in combination with --typescript)}
    {--t|typescript : Generate a TypeScript component}
TEXT;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new React component';

    public function __construct(
        protected Filesystem $files
    )
    {
        parent::__construct();
    }

    protected function pathNotAbsolute(string $path)
    {
        return ! str($path)->startsWith(DIRECTORY_SEPARATOR);
    }

    protected function getPath(string $name): string
    {
        $segments = ['js'];

        if ($this->pathNotAbsolute($name)) {
            $segments[] = 'components';
        }

        $segments[] = ltrim($name, DIRECTORY_SEPARATOR).'.'.$this->getExtension();

        return App::resourcePath(implode(DIRECTORY_SEPARATOR, $segments));
    }

    protected function getExtension(): string
    {
        return ($this->option('typescript') ? 't' : 'j').($this->option('jsx') ? 'sx' : 's');
    }

    protected function getStub(): string
    {
        $stub = 'react';
        $stub .= $this->option('typescript') ? '.ts' : '';
        $stub .= '.stub';

        $override = base_path("stubs/{$stub}");

        if ($this->files->exists($override)) {
            return $override;
        }

        return realpath(implode(DIRECTORY_SEPARATOR, [__DIR__, '..', '..', '..', 'stubs', $stub]));
    }

    protected function makeDirectory(string $path): string
    {
        $dirpath = dirname($path);

        if (! $this->files->isDirectory($dirpath)) {
            return $this->files->makeDirectory($dirpath, 0777, true, true);
        }

        return $path;
    }

    protected function buildComponent(string $name): string
    {
        $stub = $this->files->get($this->getStub());

        return str_replace('DummyComponent', basename($name), $stub);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        $path = $this->getPath($name);

        // TODO if not interactive then throw.
        if ($this->files->exists($path) && ! $this->confirm("Overwrite existing component {$name}?")) {
            return;
        }

        $this->makeDirectory($path);
        $this->files->put($this->getPath($name), $this->buildComponent($name));
        $this->info($name.' created successfully');
    }
}
