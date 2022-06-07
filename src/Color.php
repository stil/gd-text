<?php

namespace GDText;

/**
 * 8-bit RGB color representation.
 * @package GDText
 */
class Color
{
    /**
     * @var int
     */
    protected $red;

    /**
     * @var int
     */
    protected $green;

    /**
     * @var int
     */
    protected $blue;

    /**
     * @var int|null
     */
    protected $alpha;

    /**
     * @param int $red Value of red component 0-255
     * @param int $green Value of green component 0-255
     * @param int $blue Value of blue component 0-255
     * @param int $alpha A value between 0 and 127. 0 indicates completely opaque while 127 indicates completely transparent.
     */
    public function __construct($red = 0, $green = 0, $blue = 0, $alpha = null)
    {
        $this->red = $red;
        $this->green = $green;
        $this->blue = $blue;
        $this->alpha = $alpha;
    }

    /**
     * Parses string to Color object representation.
     * Modified by Gavin Lawrie @ JCOGS Design to add rbg/rgba/4/8 hex colour support
     * Updated code PRd to https://github.com/stil/gd-text on 21/Nov/2021
     * 
     * @param string $str String with color information, ex. #000000
     * @return Color
     * @todo Add parsing of CSS-like string: hsl()
     */
    public static function parseString($str)
    {
        // CSS opacity value is in range 0-1, where 1 = opaque, 0 = transparent.
        // This is the opposite of GD where value is in range 0-127 and 0 = opaque, 127 = transparent
        // Work with CSS type value until end of function and then flip into GD compatible value
        // Set default opacity to CSS friendly value of 1 (fully opaque)
        $alpha = 1;

        // Remove # if present
        $str = str_replace('#', '', $str);

        // Check to see if str is in rgb(a) format
        if (strtolower(substr($str, 0, 4)) == 'rgba') {
            preg_match('/rgba\((.*)\)/', $str, $matches);
            $values = explode(',', $matches[1]);
            if (count($values) == 4) {
                $alpha = trim(array_pop($values));
            } else {
                $alpha = $alpha ?? 1;
            }
            $rgb = $values;
        } elseif (strtolower(substr($str, 0, 3)) == 'rgb') {
            // Check to see if str is in rgb format
            preg_match('/rgb\((.*)\)/', $str, $matches);
            list($r, $g, $b) = explode(',', $matches[1]);
            $rgb = array($r, $g, $b);
        } elseif (strlen($str) == 8) {
            // Check if str is in hexadecimal format and has 8, 6, 4 or 3 characters and get values
            // If str has 8 or 4 then set alpha using the value
            $rgb = array_map('hexdec', array($str[0] . $str[1], $str[2] . $str[3], $str[4] . $str[5]));
            $alpha = round(hexdec($str[6] . $str[7]) / 255, 2);
        } elseif (strlen($str) == 6) {
            $rgb = array_map('hexdec', array($str[0] . $str[1], $str[2] . $str[3], $str[4] . $str[5]));
        } elseif (strlen($str) == 4) {
            $rgb = array_map('hexdec', array($str[0] . $str[0], $str[1] . $str[1], $str[2] . $str[2]));
            $alpha = round(hexdec($str[4] . $str[4]) / 255, 2);
        } elseif (strlen($str) == 3) {
            $rgb = array_map('hexdec', array($str[0] . $str[0], $str[1] . $str[1], $str[2] . $str[2]));
        } else {
            throw new \InvalidArgumentException('Unrecognized color.');
        }

        // Normalise rgb values if necessary
        foreach ($rgb as $color) {
            $color = $color > 255 ? 255 : $color;
            $color = $color < 0 ? 0 : $color;
        }

        //Normalise alpha value if necessary
        $alpha = $alpha > 1 ? 1 : $alpha;
        $alpha = $alpha < 0 ? 0 : $alpha;

        // Flip from CSS opacity to GD alpha form
        $alpha = (1 - $alpha) * 127;

        // Expand $rgb
        list($r, $g, $b) = $rgb;

        return new Color($r, $g, $b, $alpha);
    }

    /**
     * @param float $h Hue
     * @param float $s Saturation
     * @param float $l Light
     * @return Color
     */
    public static function fromHsl($h, $s, $l)
    {
        $fromFloat = function (array $rgb) {
            foreach ($rgb as &$v) {
                $v = (int)round($v * 255);
            };

            return new Color($rgb[0], $rgb[1], $rgb[2]);
        };

        // If saturation is 0, the given color is grey and only
        // lightness is relevant.
        if ($s == 0) {
            return $fromFloat(array($l, $l, $l));
        }

        // Else calculate r, g, b according to hue.
        // Check http://en.wikipedia.org/wiki/HSL_and_HSV#From_HSL for details
        $chroma = (1 - abs(2 * $l - 1)) * $s;
        $h_     = $h * 6;
        $x      = $chroma * (1 - abs((fmod($h_, 2)) - 1)); // Note: fmod because % (modulo) returns int value!!
        $m = $l - round($chroma / 2, 10); // Bugfix for strange float behaviour (e.g. $l=0.17 and $s=1)

        if ($h_ >= 0 && $h_ < 1) $rgb = array(($chroma + $m), ($x + $m), $m);
        elseif ($h_ >= 1 && $h_ < 2) $rgb = array(($x + $m), ($chroma + $m), $m);
        elseif ($h_ >= 2 && $h_ < 3) $rgb = array($m, ($chroma + $m), ($x + $m));
        elseif ($h_ >= 3 && $h_ < 4) $rgb = array($m, ($x + $m), ($chroma + $m));
        elseif ($h_ >= 4 && $h_ < 5) $rgb = array(($x + $m), $m, ($chroma + $m));
        elseif ($h_ >= 5 && $h_ < 6) $rgb = array(($chroma + $m), $m, ($x + $m));
        else throw new \InvalidArgumentException('Invalid hue, it should be a value between 0 and 1.');

        return $fromFloat($rgb);
    }

    /**
     * @param resource|\GdImage $image GD image resource
     * @return int Returns the index of the specified color+alpha in the palette of the image,
     *             or index of allocated color if the color does not exist in the image's palette.
     */
    public function getIndex($image)
    {
        $index = $this->hasAlphaChannel()
            ? imagecolorexactalpha(
                $image,
                round($this->red,0),
                round($this->green,0),
                round($this->blue,0),
                round($this->alpha,0)
            )
            : imagecolorexact(
                $image,
                round($this->red,0),
                round($this->green,0),
                round($this->blue,0)
            );

        if ($index !== -1) {
            return $index;
        }

        return $this->hasAlphaChannel()
            ? imagecolorallocatealpha(
                $image,
                round($this->red,0),
                round($this->green,0),
                round($this->blue,0),
                round($this->alpha,0)
            )
            : imagecolorallocate(
                $image,
                round($this->red,0),
                round($this->green,0),
                round($this->blue,0),
            );
    }

    /**
     * @return bool TRUE when alpha channel is specified, FALSE otherwise
     */
    public function hasAlphaChannel()
    {
        return $this->alpha !== null;
    }

    /**
     * @return int[]
     */
    public function toArray()
    {
        return array($this->red, $this->green, $this->blue);
    }
}
