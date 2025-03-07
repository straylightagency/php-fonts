<?php

namespace Straylightagency\Fonts;

use Closure;
use LogicException;
use Straylightagency\Fonts\Drivers\BunnyDriver;
use Straylightagency\Fonts\Drivers\GoogleDriver;
use Straylightagency\Fonts\Drivers\GoogleV2Driver;
use Straylightagency\PhpFonts\Drivers\AdobeFontsDriver;
use Straylightagency\PhpFonts\Drivers\FontAwesomeDriver;

/**
 * Helper class that help to render HTML tags to load fonts from services like Google Fonts or Bunny Fonts.
 *
 * @package Straylightagency\Fonts
 * @author Anthony Pauwels <anthony@straylightagency.be>
 */
class FontsManager
{
    /** @var string */
    protected string $default = 'google-v2';

    /** @var array */
    protected array $drivers = [];

    /** @var array */
    protected array $customDrivers = [];

    /** @var array|string[] */
    const array DRIVERS_CLASSES = [
        GoogleDriver::class => 'google',
        GoogleV2Driver::class => 'google-v2',
        BunnyDriver::class => 'bunny',
        AdobeFontsDriver::class => 'adobe',
        FontAwesomeDriver::class => 'fontawesome',
    ];

    /**
     * @param string $driver_name
     * @return $this
     */
    public function setDefault(string $driver_name): static
    {
        $this->default = $driver_name;

        return $this;
    }

    /**
     * @param string $driver_name
     * @param Closure $closureCall
     * @return $this
     */
    public function withDriver(string $driver_name, Closure $closureCall): static
    {
        $this->customDrivers[ $driver_name ] = $closureCall;

        return $this;
    }

    /**
     * @return string
     */
    public function generate(): string
    {
        $buffer = '';

        foreach ( $this->drivers as $driver ) {
            $buffer .= $driver->generate();
        }

        return $buffer;
    }

    /**
     * @return void
     */
    public function print(): void
    {
        echo $this->generate();
    }

    /**
     * @param string $driver_name
     * @return Driver
     */
    public function use(string $driver_name): Driver
    {
        $driver_name = class_exists( $driver_name ) && isset( self::DRIVERS_CLASSES[ $driver_name ] ) ? self::DRIVERS_CLASSES[ $driver_name ] : $driver_name;

        if ( isset( $this->drivers[ $driver_name ] ) ) {
            return $this->drivers[ $driver_name ];
        }

        $driver = match ($driver_name) {
            GoogleDriver::class, 'google' => $this->createGoogleDriver(),
            GoogleV2Driver::class, 'google-v2' => $this->createGoogleV2Driver(),
            BunnyDriver::class, 'bunny' => $this->createBunnyDriver(),
            AdobeFontsDriver::class, 'adobe' => $this->createAdobeFontsDriver(),
            FontAwesomeDriver::class, 'fontawesome' => $this->createFontAwesomeDriver(),
            default => $this->createCustomDriver( $driver_name ),
        };

        return $this->drivers[ $driver_name ] = $driver;
    }

    /**
     * @param string $font_name
     * @param string|array $font_weights
     * @return $this
     */
    public function load(string $font_name, string|array $font_weights = [ Fonts::regular ] ): static
    {
        $this->use( $this->default )->load( $font_name, $font_weights );

        return $this;
    }

    /**
     * @param string $font_name
     * @param string|array $font_weights
     * @return $this
     */
    public function google(string $font_name, string|array $font_weights = [ Fonts::regular ] ): static
    {
        $this->use( GoogleDriver::class )->load( $font_name, $font_weights );

        return $this;
    }

    /**
     * @param string $font_name
     * @param string|array $font_weights
     * @return $this
     */
    public function googleV2(string $font_name, string|array $font_weights = [ Fonts::regular ] ): static
    {
        $this->use( GoogleV2Driver::class )->load( $font_name, $font_weights );

        return $this;
    }

    /**
     * @param string $font_name
     * @param string|array $font_weights
     * @return $this
     */
    public function bunny(string $font_name, string|array $font_weights = [ Fonts::regular ] ): static
    {
        $this->use( BunnyDriver::class )->load( $font_name, $font_weights );

        return $this;
    }

    /**
     * @param string $kit_id
     * @return $this
     */
    public function adobe(string $kit_id): static
    {
        $this->use( AdobeFontsDriver::class )->load( $kit_id );

        return $this;
    }

    /**
     * @param string $kit_id
     * @return $this
     */
    public function fontawesome(string $kit_id): static
    {
        $this->use( FontAwesomeDriver::class )->load( $kit_id );

        return $this;
    }

    /**
     * @return GoogleDriver
     */
    protected function createGoogleDriver(): GoogleDriver
    {
        return new GoogleDriver;
    }

    /**
     * @return GoogleV2Driver
     */
    protected function createGoogleV2Driver(): GoogleV2Driver
    {
        return new GoogleV2Driver;
    }

    /**
     * @return BunnyDriver
     */
    protected function createBunnyDriver(): BunnyDriver
    {
        return new BunnyDriver;
    }

    /**
     * @return AdobeFontsDriver
     */
    protected function createAdobeFontsDriver(): AdobeFontsDriver
    {
        return new AdobeFontsDriver;
    }

    /**
     * @return FontAwesomeDriver
     */
    protected function createFontAwesomeDriver(): FontAwesomeDriver
    {
        return new FontAwesomeDriver;
    }

    /**
     * @param string $driver_name
     * @return Driver
     */
    protected function createCustomDriver(string $driver_name): Driver
    {
        if ( isset( $this->customAdapters[ $driver_name ] ) ) {
            return $this->customAdapters[ $driver_name ]();
        }

        throw new LogicException( sprintf( 'Custom Fonts Driver with name "%s" does not exist', $driver_name ), 500 );
    }
}