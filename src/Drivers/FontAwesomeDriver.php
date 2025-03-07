<?php

namespace Straylightagency\PhpFonts\Drivers;

use Straylight\Fonts\Driver;
use Straylight\Fonts\Fonts;

/**
 *
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