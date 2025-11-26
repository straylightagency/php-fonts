<?php

namespace Straylightagency\Fonts\Drivers;

use Straylightagency\Fonts\Driver;
use Straylightagency\Fonts\Fonts;

/**
 * Load fonts through the Google Fonts service using the older API.
 *
 * @see https://fonts.google.com/
 *
 * @package Straylightagency\Fonts
 * @author Anthony Pauwels <anthony@straylightagency.be>
 */
class GoogleDriver extends Driver
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

        $buffer .= '<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=';

        foreach ( $this->fonts as $name => $weights ) {
            if ( is_array( $weights ) ) {
                /** Allow to create a range of weights like 300...600 = 300,400,500,600, */
                if ( str_contains( $weights[0], '..' ) ) {
                    [ $start, $end ] = explode('..', $weights[0] );

                    $weights = range( $start, $end, 100 );
                }

                $weights = implode( ',', $weights );
            }

            /** Compact every element into a query string */
            $fonts[] = $name . ':' . $weights;

            unset( $this->fonts[ $name ] );
        }

        return $buffer . implode('|', $fonts ) . '&display=swap">' . "\n";
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