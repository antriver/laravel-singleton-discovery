<?php

namespace Antriver\LaravelSingletonDiscovery;

use Illuminate\Support\Str;
use ReflectionClass;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

class DiscoverSingletons
{
    /**
     * Get all of the events and listeners by searching the given listener directory.
     *
     * @param string $listenerPath
     * @param string $basePath
     *
     * @return array
     */
    public static function within($listenerPath, $basePath)
    {
        return collect(
            static::getListenerEvents(
                (new Finder)->files()->in($listenerPath),
                $basePath
            )
        )->mapToDictionary(
            function ($event, $listener) {
                return [$event => $listener];
            }
        )->all();
    }

    /**
     * Get all of the listeners and their corresponding events.
     *
     * @param iterable $classes
     * @param string $basePath
     *
     * @return array
     */
    protected static function getListenerEvents($classes, $basePath)
    {
        $singletonClasses = [];

        foreach ($classes as $class) {
            $reflection = new ReflectionClass(
                static::classFromFile($class, $basePath)
            );

            if (!$reflection->isSubclassOf(SingletonInterface::class)) {
                continue;
            }

            if (!$reflection->isInstantiable()) {
                continue;
            }

            $singletonClasses[] = $reflection->getName();
        }

        return array_filter($singletonClasses);
    }

    /**
     * Extract the class name from the given file path.
     *
     * @param \SplFileInfo $file
     * @param string $basePath
     *
     * @return string
     */
    protected static function classFromFile(SplFileInfo $file, $basePath)
    {
        $class = trim(Str::replaceFirst($basePath, '', $file->getRealPath()), DIRECTORY_SEPARATOR);

        return str_replace(
            [DIRECTORY_SEPARATOR, ucfirst(basename(app()->path())).'\\'],
            ['\\', app()->getNamespace()],
            ucfirst(Str::replaceLast('.php', '', $class))
        );
    }
}
