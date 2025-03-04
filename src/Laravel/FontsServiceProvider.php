<?php

namespace Straylight\Fonts\Laravel;

use Illuminate\Support\ServiceProvider;
use Straylight\Fonts\FontsManager;

/**
 * Register the FontsManager inside the Laravel Dependency Container
 *
 * @package Straylight\Fonts
 * @author Anthony Pauwels <anthony@straylightagency.be>
 */
class FontsServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(FontsManager::class, fn () => new FontsManager );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides():array
    {
        return [ FontsManager::class ];
    }
}