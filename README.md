# laravel-react-make

**This is an Alpha release expect breaking changes ahead**

Artisan generator for React function components. Supports Laravel 7, 6 &amp; 5.8.

## Quick start

```
% cd /path/to/laravel/project
% composer require --dev 77gears/laravel-react-make
```

### Customise Stubs

Publishing stubs in Laravel 7 is a [great feature](https://laravel.com/docs/7.x/artisan#stub-customization).
Yes please.

```
% php artisan vendor:publish --tag react-stub
```

## Usage

Generate a React function component:

```
% php artisan react:component MyComponent
# -> resources/js/components/MyComponent.js
```

Generate under a subdirectory (short version `-d` is also available):

```
% php artisan react:component --dir='foo/bar' MyComponent
# -> resources/js/components/foo/bar/MyComponent.js
```

Use `.jsx` file extension (short version `-x` is also available)

```
% php artisan react:component --jsx MyComponent
# -> resources/js/components/MyComponent.jsx
```
