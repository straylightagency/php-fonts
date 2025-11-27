<?php

namespace Straylightagency\Fonts;

use Closure;
use Straylightagency\Fonts\Drivers\BunnyFontsDriver;
use Straylightagency\Fonts\Drivers\GoogleFontsDriver;
use Straylightagency\Fonts\Drivers\GoogleFontsV2Driver;
use Straylightagency\Fonts\Drivers\AdobeFontsDriver;
use Straylightagency\Fonts\Drivers\FontAwesomeDriver;

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
        GoogleFontsDriver::class => 'google',
        GoogleFontsV2Driver::class => 'google-v2',
        BunnyFontsDriver::class => 'bunny',
        AdobeFontsDriver::class => 'adobe',
        FontAwesomeDriver::class => 'fontawesome',
    ];

    /**
     * Define the default driver used
     *
     * @param string $driver_name
     * @return $this
     */
    public function setDefault(string $driver_name): static
    {
        $this->default = $driver_name;

        return $this;
    }

    /**
     * Set a new driver in the manager
     *
     * @param string $driver_name
     * @param Closure $closure
     * @return $this
     */
    public function withDriver(string $driver_name, Closure $closure): static
    {
        $this->customDrivers[ $driver_name ] = $closure;

        return $this;
    }

    /**
     * Generate the HTML code
     *
     * @return string
     */
    public function toHtml(): string
    {
        $buffer = '';

        /** @var Driver $driver */
        foreach ( $this->drivers as $driver ) {
            $buffer .= $driver->toHtml();
        }

        return $buffer;
    }

    /**
     * Print the generated HTML code
     *
     * @return void
     */
    public function print(): void
    {
        echo $this->toHtml();
    }

    /**
     * Print the HTML code of the class is cast to string
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->toHtml();
    }

    /**
     * Get a driver by his name
     *
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
            GoogleFontsDriver::class, 'google' => $this->createGoogleFontsDriver(),
            GoogleFontsV2Driver::class, 'google-v2' => $this->createGoogleFontsV2Driver(),
            BunnyFontsDriver::class, 'bunny' => $this->createBunnyFontsDriver(),
            AdobeFontsDriver::class, 'adobe' => $this->createAdobeFontsDriver(),
            FontAwesomeDriver::class, 'fontawesome' => $this->createFontAwesomeDriver(),
            default => $this->createCustomDriver( $driver_name ),
        };

        return $this->drivers[ $driver_name ] = $driver;
    }

    /**
     * Load a font through the default driver
     *
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
     * Load a Google Font
     *
     * @param string $font_name
     * @param string|array $font_weights
     * @return $this
     */
    public function google(string $font_name, string|array $font_weights = [ Fonts::regular ] ): static
    {
        $this->use( GoogleFontsDriver::class )->load( $font_name, $font_weights );

        return $this;
    }

    /**
     * Load a Google Font using the API V2
     *
     * @param string $font_name
     * @param string|array $font_weights
     * @return $this
     */
    public function googleV2(string $font_name, string|array $font_weights = [ Fonts::regular ] ): static
    {
        $this->use( GoogleFontsV2Driver::class )->load( $font_name, $font_weights );

        return $this;
    }

    /**
     * Load a font using the Bunny Fonts service
     *
     * @param string $font_name
     * @param string|array $font_weights
     * @return $this
     */
    public function bunny(string $font_name, string|array $font_weights = [ Fonts::regular ] ): static
    {
        $this->use( BunnyFontsDriver::class )->load( $font_name, $font_weights );

        return $this;
    }

    /**
     * Load a kit using the Adobe Fonts / Typekit service
     *
     * @param string $kit_id
     * @return $this
     */
    public function adobe(string $kit_id): static
    {
        $this->use( AdobeFontsDriver::class )->kit( $kit_id );

        return $this;
    }

    /**
     * Load an icon font using the Fontawesome service
     *
     * @param string $kit_id
     * @return $this
     */
    public function fontawesome(string $kit_id): static
    {
        $this->use( FontAwesomeDriver::class )->kit( $kit_id );

        return $this;
    }

    /**
     * Create a new Google Fonts Driver
     *
     * @return GoogleFontsDriver
     */
    protected function createGoogleFontsDriver(): GoogleFontsDriver
    {
        return new GoogleFontsDriver;
    }

    /**
     * Create a new Google Fonts V2 Driver
     *
     * @return GoogleFontsV2Driver
     */
    protected function createGoogleFontsV2Driver(): GoogleFontsV2Driver
    {
        return new GoogleFontsV2Driver;
    }

    /**
     * Create a new Bunny Fonts Driver
     *
     * @return BunnyFontsDriver
     */
    protected function createBunnyFontsDriver(): BunnyFontsDriver
    {
        return new BunnyFontsDriver;
    }

    /**
     * Create a new Adobe Fonts Driver
     *
     * @return AdobeFontsDriver
     */
    protected function createAdobeFontsDriver(): AdobeFontsDriver
    {
        return new AdobeFontsDriver;
    }

    /**
     * Create a new FontAwesome Driver
     *
     * @return FontAwesomeDriver
     */
    protected function createFontAwesomeDriver(): FontAwesomeDriver
    {
        return new FontAwesomeDriver;
    }

    /**
     * Create a new custom fonts driver
     *
     * @param string $driver_name
     * @return Driver
     */
    protected function createCustomDriver(string $driver_name): Driver
    {
        if ( isset( $this->customAdapters[ $driver_name ] ) ) {
            return $this->customAdapters[ $driver_name ]();
        }

        throw new FontsException( sprintf( 'Custom Fonts Driver with name "%s" does not exist', $driver_name ), 500 );
    }
}