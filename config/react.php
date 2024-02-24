<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Base Path
    |--------------------------------------------------------------------------
    |
    | The base for absolute paths, onto which the path prefix will be added for
    | relative paths. This will most likely be the directory in which you store
    | your JavaScript or TypeScript source code.
    |
    */
    'base' => resource_path('js'),

    /*
    |--------------------------------------------------------------------------
    | Path Prefix
    |--------------------------------------------------------------------------
    |
    | Change the default path prefix under resources/js used by the command
    | when a relative path is provided as the name argument.
    |
    */
    'prefix' => 'components',

    /*
    |--------------------------------------------------------------------------
    | Default Options
    |--------------------------------------------------------------------------
    |
    | Provide default options to be passed to the command when it is called.
    | For example, to always use TypeScript and the file extension .ts
    | then change the value to ['typescript' => true, 'extension' => 'ts'].
    |
    */
    'defaults' => [],
];
