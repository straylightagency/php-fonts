<?php

namespace Straylight\Fonts\Laravel;

use Closure;
use Illuminate\Support\Facades\Facade;
use Straylight\Fonts\FontsManager;
use Straylight\Fonts\Fonts as BaseFonts;

/**
 * Facade.
 * Provide quick access methods to the FontsManager class
 *
 * @method static FontsManager setDefault(string $driver_name)
 * @method static FontsManager withDriver(string $driver_name, Closure $closureCall)
 * @method static FontsManager generate()
 * @method static FontsManager print()
 * @method static FontsManager use(string $driver_name)
 * @method static FontsManager load(string $font_name, string|array $font_weights = [ BaseFonts::regular ])
 * @method static FontsManager google(string $font_name, string|array $font_weights = [ BaseFonts::regular ])
 * @method static FontsManager googleV2(string $font_name, string|array $font_weights = [ BaseFonts::regular ])
 * @method static FontsManager bunny(string $font_name, string|array $font_weights = [ BaseFonts::regular ])
 * @method static FontsManager fontawesome(string $kit_id)
 *
 * @package Straylight\Fonts
 * @author Anthony Pauwels <anthony@straylightagency.be>
 */
class Fonts extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return FontsManager::class;
    }
}