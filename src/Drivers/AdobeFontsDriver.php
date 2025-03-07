<?php

namespace Straylightagency\PhpFonts\Drivers;

use Straylightagency\Fonts\Driver;
use Straylightagency\Fonts\Fonts;

/**
 *
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
        $this->kits[ strtolower( $font_name ) ] = $font_name;

        return $this;
    }

    /**
     * @return string
     */
    public function generate(): string
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