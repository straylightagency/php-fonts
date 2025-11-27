<?php

namespace Straylightagency\Fonts\Drivers;

use Straylightagency\Fonts\Fonts;
use Straylightagency\Fonts\Driver;

/**
 * Load icon fonts from Adobe Fonts service using a kit ID
 *
 * @see https://fonts.adobe.com/
 *
 * @package Straylightagency\Fonts
 * @author Anthony Pauwels <anthony@straylightagency.be>
 */
class AdobeFontsDriver extends Driver
{
    /** @var array */
    protected array $kits = [];

    /**
     * @param string $font_name
     * @param string|array $font_weights
     * @return $this
     */
    public function load(string $font_name, string|array $font_weights = [ Fonts::regular ] ): static
    {
        return $this->kit( $font_name );
    }

    /**
     * @param string $kit_id
     * @return $this
     */
    public function kit(string $kit_id): static
    {
        $this->kits[ $kit_id ] = $kit_id;

        return $this;
    }

    /**
     * @return string
     */
    public function toHtml(): string
    {
        if ( empty( $this->kits ) ) {
            return '';
        }

        $buffer = '';

        if ( !$this->isInitialized() ) {
            $buffer .= $this->preconnect();
            $this->isInitialized( true );
        }

        $kits = array_map( function ( string $id ) {
            unset( $this->kits[ $id ] );

            return '<link rel="stylesheet" href="https://use.typekit.net/' . $id . '.css">';
        }, $this->kits );

        $buffer.= implode( "\n", $kits );

        return $buffer . "\n";
    }

    /**
     * @return string
     */
    public function preconnect(): string
    {
        return '<link rel="preconnect" href="https://use.typekit.net">' . "\n";
    }
}