<?php namespace Waavi\ResponseCache\Commands;

use Illuminate\Config\Repository as Config;
use Illuminate\Console\Command;
use Waavi\ResponseCache\Cache\RepositoryInterface;

class CacheClearCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'responsecache:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Clear the http response cache.";

    /**
     *  Create the cache flushed command
     *
     *  @param  \Waavi\Lang\Providers\LanguageProvider        $languageRepository
     *  @param  \Waavi\Lang\Providers\LanguageEntryProvider   $translationRepository
     *  @param  \Illuminate\Foundation\Application            $app
     */
    public function __construct(RepositoryInterface $repository, Config $config)
    {
        parent::__construct();
        $this->repository = $repository;
        $this->enabled    = $config->get('responsecache.enabled', false);
    }

    /**
     *  Execute the console command.
     *
     *  @return void
     */
    public function fire()
    {
        if (!$this->enabled) {
            $this->info('The response cache is disabled.');
        } else {
            $this->repository->clear();
            $this->info('Response cache cleared.');
        }
    }
}
