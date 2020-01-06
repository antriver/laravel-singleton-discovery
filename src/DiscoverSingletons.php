<?php

namespace Antriver\LaravelSingletonDiscovery;

use Illuminate\Support\Str;
use ReflectionClass;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

class DiscoverSingletons
{
    /**
     * Get all of the events and listeners by searching the given directory.
     *
     * @param string $path
     * @param string $basePath
     * @param string $baseNamespace
     *
     * @return array
     */
    public static function within(string $path, string $basePath, string $baseNamespace)
    {
        return collect(
            static::getSingletonClasses(
                (new Finder)->files()->in($path),
                $basePath,
                $baseNamespace
            )
        )->all();
    }

    /**
     * Get all of the listeners and their corresponding events.
     *
     * @param SplFileInfo[]|iterable $classes
     * @param string $basePath
     *
     * @param string $baseNamespace
     *
     * @return string[]
     */
    protected static function getSingletonClasses($classes, string $basePath, string $baseNamespace)
    {
        $singletonClasses = [];

        foreach ($classes as $class) {
            $className = static::getClassNameFromFile($class, $basePath, $baseNamespace);

            $reflection = new ReflectionClass($className);

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
     * @param string $baseNamespace
     *
     * @return string
     */
    protected static function getClassNameFromFile(SplFileInfo $file, string $basePath, string $baseNamespace): string
    {
        // Remove the basePath from the file's absolute path to get its path relative to the app root.
        $relativePath = trim(Str::replaceFirst($basePath, '', $file->getRealPath()), DIRECTORY_SEPARATOR);

        // Replace directory separators with namespace separators.
        $className = str_replace(DIRECTORY_SEPARATOR, '\\', $relativePath);

        // Class name relative to root namespace.
        $className = ucfirst(Str::replaceLast('.php', '', $className));

        // Add the root namespace.
        $className = $baseNamespace.'\\'.$className;

        return $className;
    }
}
