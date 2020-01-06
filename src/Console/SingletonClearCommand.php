<?php

namespace Antriver\LaravelSingletonDiscovery\Console;

use Antriver\LaravelSingletonDiscovery\SingletonDiscoveryServiceProvider;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class SingletonClearCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'singletons:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all cached singleton classes';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Create a new config clear command instance.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return void
     *
     * @throws \RuntimeException
     */
    public function handle()
    {
        /** @var SingletonDiscoveryServiceProvider $provider */
        $provider = $this->laravel->getProviders(SingletonDiscoveryServiceProvider::class);

        $this->files->delete($provider->getCachedSingletonsPath());

        $this->info('Cached singletons cleared!');
    }
}
