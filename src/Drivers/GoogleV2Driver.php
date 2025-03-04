<?php

namespace Straylight\Fonts\Drivers;

use Straylight\Fonts\Driver;
use Straylight\Fonts\Fonts;

/**
 * Load fonts through the Google Fonts service using the newest API "css2".
 *
 * @see https://fonts.google.com/
 *
 * @package Straylight\Fonts
 * @author Anthony Pauwels <anthony@straylightagency.be>
 */
class GoogleV2Driver extends Driver
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
        $this->fonts[ urlencode( $font_name ) ] = $font_weights;

        return $this;
    }

    /**
     * @return string
     */
    public function generate(): string
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

        $buffer .= '<link rel="stylesheet" href="https://fonts.googleapis.com/css2?';

        foreach ( $this->fonts as $name => $weights ) {
            if ( is_array( $weights ) ) {
                $styles = [];

                $styles[] = 'ital';
                $styles[] = 'wght';

                usort( $weights, function (string $a, string $b) {
                    $is_italic_a = str_contains( $a, 'i' ) ? 1 : 0;
                    $is_italic_b = str_contains( $b, 'i' ) ? 1 : 0;

                    return $is_italic_a > $is_italic_b;
                } );

                /** Create the string for the weights of the font */
                $weights = array_map( fn (string $weight) => ( str_contains( $weight, 'i' ) ? '1' : '0' ) . ',' . str_replace('i', '', $weight), $weights );

                $weights = implode( ',', $styles ) . '@' . implode( ';', $weights );
            }

            /** Compact every element into a query string */
            $fonts[] = 'family=' . $name . ':' . $weights;

            unset( $this->fonts[ $name ] );
        }

        return $buffer . implode('&', $fonts ) . '&display=swap">' . "\n";
    }

    /**
     * @return string
     */
    public function preconnect(): string
    {
        return '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n"
             . '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    }
}