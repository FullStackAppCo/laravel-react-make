# Laravel React Make

Quickly scaffold React components in your Laravel app. 

## Laravel Support

| Laravel | Laravel React Make |
|---------|--------------------|
| 10      | 4                  |
| 10, 9   | 3                  |
| 9, 8    | 2                  |
| 8, 7, 6 | 1                  |

## Installation
In your Laravel directory install via Composer.
```bash
composer require --dev fsac/laravel-react-make
```

## Basic Usage
Generate a React function component under `resources/js/components`.
```bash
php artisan make:react PrimaryButton
# -> resources/js/components/PrimaryButton.jsx
```

You may also include subdirectories:
```bash
php artisan make:react buttons/Primary
# -> resources/js/components/buttons/Primary.jsx
```

Providing an absolute path will omit the `components` prefix, using `resources/js` as the root:
```bash
php artisan make:react /pages/Settings
# -> resources/js/pages/Settings.jsx
```

## Advanced Usage

### TypeScript
The command also supports generating TypeScript components. The short version `-t` may also be used:
```bash
php artisan make:react --typescript PrimaryButton
# -> resources/js/components/PrimaryButton.tsx
```

### File Extension
You may provide a custom file extension. The short version `-x` may also be used
```bash
php artisan make:react --extension js
# -> resources/js/components/PrimaryButton.js
```

### Publishing Config
If you'd like to customise the default configuration, which includes setting default option values:

```bash
php artisan vendor:publish --tag react-config
```

### Customising Stubs
If you'd like to customise the default templates used to generate components you may publish them
to the `stubs` directory:

```bash
php artisan vendor:publish --tag react-stub
```
