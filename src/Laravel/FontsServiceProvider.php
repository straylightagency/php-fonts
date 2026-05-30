<?php

namespace Straylightagency\Fonts\Laravel;

use Illuminate\Support\ServiceProvider;
use Straylightagency\Fonts\Driver;
use Straylightagency\Fonts\FontsManager;

/**
 * Register the FontsManager inside the Laravel Dependency Container
 *
 * @package Straylightagency\Fonts
 * @author Anthony Pauwels <anthony@straylightagency.be>
 */
class FontsServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton( FontsManager::class, fn () => new FontsManager );
    }

    /**
     * @return void
     */
    public function boot(): void {
        if ( $this->app->runningInConsole() ) {
            $this->publishes( [
                __DIR__.'/config/fonts.php' => config_path('fonts.php'),
            ], 'fonts' );

            return;
        }

        $fontsConfig = config('fonts');

        $fontsManager = $this->app->get( FontsManager::class );

        foreach ( $fontsConfig['stack'] as $stack ) {
            $stackConfig = $fontsConfig[ $stack ];

            /** @var Driver $driver */
            $driver = $fontsManager->use( $stackConfig['driver'] );
            $fonts = $stackConfig['fonts'];

            foreach ( $fonts as $key => $value ) {
                if ( is_numeric( $key ) ) {
                    $driver->load( $value );
                } else {
                    $driver->load( $key, $value );
                }
            }
        }
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