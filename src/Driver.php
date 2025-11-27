<?php

namespace Straylightagency\Fonts;

/**
 * Abstract driver class
 *
 * @package Straylightagency\Fonts
 * @author Anthony Pauwels <anthony@straylightagency.be>
 */
abstract class Driver
{
    /** @var bool */
    protected bool $initialized = false;

    /**
     * @param bool|null $value
     * @return bool
     */
    public function isInitialized(?bool $value = null): bool
    {
        return $value ? $this->initialized = $value : $this->initialized;
    }

    /**
     * @param string $font_name
     * @param string|array $font_weights
     * @return static
     */
    abstract function load(string $font_name, string|array $font_weights = [ Fonts::regular ] ): static;

    /**
     * @return string
     */
    abstract function toHtml(): string;
}