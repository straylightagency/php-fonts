<?php

namespace Straylightagency\Fonts\Drivers;

use Straylightagency\Fonts\Fonts;
use Straylightagency\Fonts\Driver;

/**
 * Load icon fonts from FontAwesome service using a kit ID
 *
 * @see https://fontawesome.com/
 *
 * @package Straylightagency\Fonts
 * @author Anthony Pauwels <anthony@straylightagency.be>
 */
class FontAwesomeDriver extends Driver
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

            return '<script src="https://kit.fontawesome.com/' . $id . '.js" crossorigin="anonymous"></script>';
        }, $this->kits );

        $buffer.= implode( "\n", $kits );

        return $buffer . "\n";
    }

    /**
     * @return string
     */
    public function preconnect(): string
    {
        return '<link rel="preconnect" href="https://kit.fontawesome.com">' . "\n";
    }
}