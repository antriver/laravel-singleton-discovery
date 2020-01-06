<?php

namespace Antriver\LaravelSingletonDiscoveryTests;

use Antriver\LaravelSingletonDiscovery\DiscoverSingletons;
use PHPUnit\Framework\TestCase;

class DiscoverSingletonsTest extends TestCase
{
    public function testWithinRootDir()
    {
        $directory = __DIR__.'/Fixtures';

        $result = DiscoverSingletons::within(
            $directory,
            __DIR__.'/Fixtures',
            'Antriver\LaravelSingletonDiscoveryTests\\Fixtures'
        );

        $this->assertEquals(
            [
                'Antriver\LaravelSingletonDiscoveryTests\Fixtures\Subdirectory\IsSingleton',
                'Antriver\LaravelSingletonDiscoveryTests\Fixtures\IsSingleton',
                'Antriver\LaravelSingletonDiscoveryTests\Fixtures\AlsoSingleton',
            ],
            $result
        );
    }

    public function testWithinSubdirectory()
    {
        $directory = __DIR__.'/Fixtures/Subdirectory';

        $result = DiscoverSingletons::within(
            $directory,
            __DIR__.'/Fixtures',
            'Antriver\LaravelSingletonDiscoveryTests\\Fixtures'
        );

        $this->assertEquals(
            [
                'Antriver\LaravelSingletonDiscoveryTests\Fixtures\Subdirectory\IsSingleton',
            ],
            $result
        );
    }
}
