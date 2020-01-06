## The Problem

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

## The Solution

This package automatically scans your `app` directory for and classes that implement the `SingletonInterface` interface. For any it finds it registers them as a singleton with the DI container. This is the same pattern that Laravel's built in event discovery follows (singe 5.8.9)
