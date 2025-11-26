<?php

namespace Straylightagency\Fonts\Drivers;

use Straylightagency\Fonts\Driver;
use Straylightagency\Fonts\Fonts;

/**
 * Load fonts through the Bunny Fonts service
 *
 * @see https://fonts.bunny.net/
 *
 * @package Straylightagency\Fonts
 * @author Anthony Pauwels <anthony@straylightagency.be>
 */
class BunnyDriver extends Driver
{
    /** @var array */
    protected array $fonts = [];

    /**
     * @param string $font_name
     * @param string|array $font_weights
     * @return $this
     */
    public function load(string $font_name, string|array $font_weights = [ Fonts::regular ] ): static
    {
        $this->fonts[ urlencode( strtolower( $font_name ) ) ] = $font_weights;

        return $this;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        if ( empty( $this->fonts ) ) {
            return '';
        }

        $buffer = '';
        $fonts = [];

        if ( !$this->isInitialized() ) {
            $buffer .= $this->preconnect();
            $this->isInitialized( true );
        }

        $buffer .= '<link rel="stylesheet" href="https://fonts.bunny.net/css?family=';

        foreach ( $this->fonts as $name => $weights ) {
            if ( is_array( $weights ) ) {
                $weights = implode( ',', $weights );
            }

            /** Compact every element into a query string */
            $fonts[] = $name . ':' . $weights;

            unset( $this->fonts[ $name ] );
        }

        return $buffer . implode('|', $fonts ) . '" />' . "\n";
    }

    /**
     * @return string
     */
    public function preconnect(): string
    {
        return '<link rel="preconnect" href="https://fonts.bunny.net">' . "\n";
    }
}