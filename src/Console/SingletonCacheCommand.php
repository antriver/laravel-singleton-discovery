<?php

namespace Antriver\LaravelSingletonDiscovery\Console;

use Antriver\LaravelSingletonDiscovery\SingletonDiscoveryServiceProvider;
use Illuminate\Console\Command;

class SingletonCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'singletons:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Discover and cache the application's singleton classes";

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call('singleton:clear');

        /** @var SingletonDiscoveryServiceProvider $provider */
        $provider = $this->laravel->getProviders(SingletonDiscoveryServiceProvider::class);

        file_put_contents(
            $provider->getCachedSingletonsPath(),
            '<?php return '.var_export($this->getSingletons(), true).';'
        );

        $this->info('Singletons cached successfully!');
    }

    /**
     * Get all of the events and listeners configured for the application.
     *
     * @return array
     */
    protected function getSingletons()
    {
        $events = [];

        foreach ($this->laravel->getProviders(SingletonDiscoveryServiceProvider::class) as $provider) {
            /** @var SingletonDiscoveryServiceProvider $provider */
            $providerSingletons = $provider->discoverSingletons();

            $events[get_class($provider)] = $providerSingletons;
        }

        return $events;
    }
}
