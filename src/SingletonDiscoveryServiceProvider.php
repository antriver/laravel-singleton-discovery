<?php

namespace Antriver\LaravelSingletonDiscovery;

use Illuminate\Support\ServiceProvider;

class SingletonDiscoveryServiceProvider extends ServiceProvider
{
    public function register()
    {
        foreach ($this->getSingletons() as $class) {
            $this->app->singleton($class);
        }
    }

    /**
     * Get the discovered events and listeners for the application.
     *
     * @return array
     */
    public function getSingletons()
    {
        if ($this->singletonsAreCached()) {
            $cache = require $this->getCachedSingletonsPath();

            return $cache[get_class($this)] ?? [];
        } else {
            return $this->discoverSingletons();
        }
    }

    /**
     * Discover the events and listeners for the application.
     *
     * @return array
     */
    public function discoverSingletons()
    {
        return collect($this->discoverSingletonsWithin())
            ->reject(
                function ($directory) {
                    return !is_dir($directory);
                }
            )
            ->reduce(
                function ($discovered, $directory) {
                    return array_merge_recursive(
                        $discovered,
                        DiscoverSingletons::within($directory, base_path())
                    );
                },
                []
            );
    }

    /**
     * Get the listener directories that should be used to discover events.
     *
     * @return array
     */
    protected function discoverSingletonsWithin()
    {
        return [
            $this->app->path(),
        ];
    }

    /**
     * Determine if the application events are cached.
     *
     * @return bool
     */
    public function singletonsAreCached()
    {
        /** @var \Illuminate\Filesystem\Filesystem $files */
        $files = $this->app['files'];

        return $files->exists($this->getCachedSingletonsPath());
    }

    /**
     * Get the path to the events cache file.
     *
     * @return string
     */
    public function getCachedSingletonsPath()
    {
        return $_ENV['APP_SINGLETONS_CACHE'] ?? $this->app->bootstrapPath().'/cache/singletons.php';
    }
}
