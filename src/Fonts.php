<?php

namespace Straylight\Fonts;

use Closure;
use LogicException;
use Straylight\Fonts\Drivers\BunnyDriver;
use Straylight\Fonts\Drivers\GoogleDriver;

/**
 * List fonts weights
 *
 * @package Straylight\Fonts
 * @author Anthony Pauwels <anthony@straylightagency.be>
 */
abstract class Fonts
{
    /** @var string */
    const string thin = '100';

    /** @var string */
    const string thin_italic = '100i';

    /** @var string */
    const string extralight = '200';

    /** @var string */
    const string extralight_italic = '200i';

    /** @var string */
    const string light = '300';

    /** @var string */
    const string light_italic = '300i';

    /** @var string */
    const string regular = '400';

    /** @var string */
    const string regular_italic = '400i';

    /** @var string */
    const string medium = '500';

    /** @var string */
    const string medium_italic = '500i';

    /** @var string */
    const string semibold = '600';

    /** @var string */
    const string semibold_italic = '600i';

    /** @var string */
    const string bold = '700';

    /** @var string */
    const string bold_italic = '700i';

    /** @var string */
    const string extrabold = '800';

    /** @var string */
    const string extrabold_italic = '800i';

    /** @var string */
    const string black = '900';

    /** @var string */
    const string black_italic = '900i';
}