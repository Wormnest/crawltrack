<?php

/*
 * This work is hereby released into the Public Domain.
 * To view a copy of the public domain dedication,
 * visit http://creativecommons.org/licenses/publicdomain/ or send a letter to
 * Creative Commons, 559 Nathan Abbott Way, Stanford, California 94305, USA.
 *
 */

/**
 * Create your colors
 *
 * @package Artichow
 */
class awColor
{
    public $red;
    public $green;
    public $blue;
    public $alpha;

    /**
     * Build your color
     *
     * @var int $red Red intensity (from 0 to 255)
     * @var int $green Green intensity (from 0 to 255)
     * @var int $blue Blue intensity (from 0 to 255)
     * @var int $alpha Alpha channel (from 0 to 100)
     */
    public function __construct($red, $green, $blue, $alpha = 0)
    {

        $this->red   = (int) $red;
        $this->green = (int) $green;
        $this->blue  = (int) $blue;
        $this->alpha = (int) round($alpha * 127 / 100);
    }

    /**
     * Get RGB and alpha values of your color
     *
     * @return array
     */
    public function getColor()
    {
        return $this->rgba();
    }

    /**
     * Change color brightness
     *
     * @param int $brightness Add this intensity to the color (betweeen -255 and +255)
     */
    public function brightness($brightness)
    {

        $brightness = (int) $brightness;

        $this->red   = min(255, max(0, $this->red + $brightness));
        $this->green = min(255, max(0, $this->green + $brightness));
        $this->blue  = min(255, max(0, $this->blue + $brightness));
    }

    /**
     * Get RGB and alpha values of your color
     *
     * @return array
     */
    public function rgba()
    {

        return array($this->red, $this->green, $this->blue, $this->alpha);
    }

}

class awBlack extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(0, 0, 0, $alpha);
    }

}

class awAlmostBlack extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(48, 48, 48, $alpha);
    }

}

class awVeryDarkGray extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(88, 88, 88, $alpha);
    }

}

class awDarkGray extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(128, 128, 128, $alpha);
    }

}

class awMidGray extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(160, 160, 160, $alpha);
    }

}

class awLightGray extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(195, 195, 195, $alpha);
    }

}

class awVeryLightGray extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(220, 220, 220, $alpha);
    }

}

class awWhite extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(255, 255, 255, $alpha);
    }

}

class awVeryDarkRed extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(64, 0, 0, $alpha);
    }

}

class awDarkRed extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(128, 0, 0, $alpha);
    }

}

class awMidRed extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(192, 0, 0, $alpha);
    }

}

class awRed extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(255, 0, 0, $alpha);
    }

}

class awLightRed extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(255, 192, 192, $alpha);
    }

}

class awVeryDarkGreen extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(0, 64, 0, $alpha);
    }

}

class awDarkGreen extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(0, 128, 0, $alpha);
    }

}

class awMidGreen extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(0, 192, 0, $alpha);
    }

}

class awGreen extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(0, 255, 0, $alpha);
    }

}

class awLightGreen extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(192, 255, 192, $alpha);
    }

}

class awVeryDarkBlue extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(0, 0, 64, $alpha);
    }

}

class awDarkBlue extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(0, 0, 128, $alpha);
    }

}

class awMidBlue extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(0, 0, 192, $alpha);
    }

}

class awBlue extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(0, 0, 255, $alpha);
    }

}

class awLightBlue extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(192, 192, 255, $alpha);
    }

}

class awVeryDarkYellow extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(64, 64, 0, $alpha);
    }

}

class awDarkYellow extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(128, 128, 0, $alpha);
    }

}

class awMidYellow extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(192, 192, 0, $alpha);
    }

}

class awYellow extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(255, 255, 2, $alpha);
    }

}

class awLightYellow extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(255, 255, 192, $alpha);
    }

}

class awVeryDarkCyan extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(0, 64, 64, $alpha);
    }

}

class awDarkCyan extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(0, 128, 128, $alpha);
    }

}

class awMidCyan extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(0, 192, 192, $alpha);
    }

}

class awCyan extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(0, 255, 255, $alpha);
    }

}

class awLightCyan extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(192, 255, 255, $alpha);
    }

}

class awVeryDarkMagenta extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(64, 0, 64, $alpha);
    }

}

class awDarkMagenta extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(128, 0, 128, $alpha);
    }

}

class awMidMagenta extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(192, 0, 192, $alpha);
    }

}

class awMagenta extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(255, 0, 255, $alpha);
    }

}

class awLightMagenta extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(255, 192, 255, $alpha);
    }

}

class awDarkOrange extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(192, 88, 0, $alpha);
    }

}

class awOrange extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(255, 128, 0, $alpha);
    }

}

class awLightOrange extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(255, 168, 88, $alpha);
    }

}

class awVeryLightOrange extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(255, 220, 168, $alpha);
    }

}

class awDarkPink extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(192, 0, 88, $alpha);
    }

}

class awPink extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(255, 0, 128, $alpha);
    }

}

class awLightPink extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(255, 88, 168, $alpha);
    }

}

class awVeryLightPink extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(255, 168, 220, $alpha);
    }

}

class awDarkPurple extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(88, 0, 192, $alpha);
    }

}

class awPurple extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(128, 0, 255, $alpha);
    }

}

class awLightPurple extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(168, 88, 255, $alpha);
    }

}

class awVeryLightPurple extends awColor
{

    public function __construct($alpha = 0)
    {
        parent::__construct(220, 168, 255, $alpha);
    }

}
