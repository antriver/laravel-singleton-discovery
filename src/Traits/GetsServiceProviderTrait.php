<?php

namespace Antriver\LaravelSingletonDiscovery\Traits;

use Antriver\LaravelSingletonDiscovery\SingletonDiscoveryServiceProvider;

trait GetsServiceProviderTrait
{
    /**
     * @return SingletonDiscoveryServiceProvider
     * @throws \Exception
     */
    protected function getSingletonDiscoveryServiceProvider(): SingletonDiscoveryServiceProvider
    {
        /** @var SingletonDiscoveryServiceProvider[] $providers */
        $providers = $this->laravel->getProviders(SingletonDiscoveryServiceProvider::class);

        if (empty($providers)) {
            throw new \Exception("No instances of SingletonDiscoveryServiceProvider are registered.");
        }

        return reset($providers);
    }
}
