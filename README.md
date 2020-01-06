# Laravel Singleton Auto Discovery

Automatically registers your classes as singletons in Laravel's DI container.

### The Problem

In Laravel by default if you have something injected by calling `app(SomeClass::class)` or `App::make(SomeClass::class)` or by a type hint on a method, it will give you a new instance of that class each time.
e.g.
```php
app(SomeClass::class) === app(SomeClass::class); // false
```

To solve this you need to explicitly tell the DI container that you want `SomeClass` to be a singleton, like this:
```php
app()->singleton(SomeClass::class);
``` 

If you have lots of classes you want to be singletons it gets tedious to have to write the `app()->singleton()` call for every one.

### The Solution

This package automatically scans your `app` directory for any classes that implement the `SingletonInterface` interface. For any it finds it registers them as a singleton with the DI container.

This is very similar to the pattern that Laravel's built in event discovery follows (since 5.8.9).

## Installation

```
composer require antriver/laravel-singleton-discovery
```

Add `SingletonDiscoveryServiceProvider` to your list of providers in `config/app.php`:
```php
    'providers' => [
        // ...
        Antriver\LaravelSingletonDiscovery\SingletonDiscoveryServiceProvider::class,
        // ...
    ],
```

## Commands

The directory will be scanned each time the app starts up. This is probably what you want during development but will affect performance in production. You can use the `singletons:cache` command to cache the discovered singletons to avoid that. The `singletons:clear` command clears the cached file.
