<?php

declare(strict_types=1);

namespace Fox\Application\Version;

use Illuminate\Contracts\Cache\Repository as CacheContract;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;

/**
 * Class ServiceProvider.
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register any application services.
     * {@inheritdoc}
     *
     * @return void
     *
     * @codeCoverageIgnore
     */
    public function register()
    {
        $this->app->singleton(VersionManager::class, function (ApplicationContract $app) {
            return new VersionManager(
                $app->make(CacheContract::class),
                3600 * 8,
                $app->basePath() . DIRECTORY_SEPARATOR . 'composer.json'
            );
        });
        $this->app->alias(VersionManager::class, 'version-manager');
    }
}
