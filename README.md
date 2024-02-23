---
published: true
title: Laravel React Make
description: Artisan command to generate React components with support for TypeScript.
license: MIT
source: FullStackAppCo/laravel-react-make
pkg: fsac/laravel-react-make
---

## Laravel Support

| Laravel | Laravel React Make |
|---------|--------------------|
| 10, 9   | 3                  |
| 9, 8    | 2                  |
| 8, 7, 6 | 1                  |

## Installation
In your Laravel directory install via Composer.
```bash
composer require --dev fsac/laravel-react-make
```

## Basic Usage
Generate a React function component in the file `MyComponent.js` under `resources/js/components`.
```bash
php artisan make:react PrimaryButton
# -> resources/js/components/PrimaryButton.js
```

You may also include subdirectories:
```bash
php artisan make:react buttons/Primary
# -> resources/js/components/buttons/Primary.js
```

Providing an absolute path will omit the `components` prefix, using `resources/js` as the root:
```bash
php artisan make:react /pages/Settings
# -> resources/js/pages/Settings.js
```

## Advanced Usage
Use `.jsx` file extension instead of the default `.js`. The short version `-x` may also be used:
```bash
php artisan make:react --jsx PrimaryButton
# -> resources/js/components/PrimaryButton.jsx
```

### TypeScript
The command also supports generating TypeScript components. The short version `-t` may also be used:
```bash
php artisan make:react --typescript PrimaryButton
# -> resources/js/components/PrimaryButton.ts
```

When used in combination with the `--jsx` option the `.tsx` suffix will be used. The following
example uses short option variants:
```bash
php artisan make:react -tx PrimaryButton
# -> resources/js/components/PrimaryButton.tsx
```

### Publishing Config
If you'd like to customise the default configuration, including setting default option values:

```bash
php artisan vendor:publish --tag react-config
```

### Customising Stubs
If you'd like to customise the default templates used to generate components you may publish them
to the `stubs` directory:

```bash
php artisan vendor:publish --tag react-stub
```
