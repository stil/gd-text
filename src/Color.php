<?php
namespace GDText;

/**
 * 8-bit RGB color representation.
 * @package GDText
 */
class Color
{

    protected int $red;
    protected int $green;
    protected int $blue;
    protected ?int $alpha;

    /**
     * @param int $red Value of red component 0-255
     * @param int $green Value of green component 0-255
     * @param int $blue Value of blue component 0-255
     * @param int|null $alpha A value between 0 and 127. 0 indicates completely opaque while 127 indicates completely transparent.
     */
    public function __construct(int $red = 0, int $green = 0, int $blue = 0, int $alpha = null)
    {
        $this->red = $red;
        $this->green = $green;
        $this->blue = $blue;
        $this->alpha = $alpha;
    }

    /**
     * Parses string to Color object representation.
     * @param string $string String with color information, ex. #000000
     * @return Color
     * @todo Add parsing of CSS-like strings: rgb(), rgba(), hsl()
     */
    public static function parseString(string $string) : Color
    {
        $string = str_replace('#', '', $string);

        if (strlen($string) == 6) {
            $r = hexdec(substr($string, 0, 2));
            $g = hexdec(substr($string, 2, 2));
            $b = hexdec(substr($string, 4, 2));
        }
        else if (strlen($string) == 3) {
            $r = hexdec(str_repeat(substr($string, 0, 1), 2));
            $g = hexdec(str_repeat(substr($string, 1, 1), 2));
            $b = hexdec(str_repeat(substr($string, 2, 1), 2));
        }
        else {
            throw new \InvalidArgumentException('Unrecognized color.');
        }

        return new Color($r, $g, $b);
    }

    public static function fromHsl(float $hue, float $saturation, float $light) : Color
    {
        $fromFloat = function (array $rgb) {
            foreach ($rgb as &$v) {
                $v = (int)round($v * 255);
            }

            return new Color($rgb[0], $rgb[1], $rgb[2]);
        };

        // If saturation is 0, the given color is grey and only
        // lightness is relevant.
        if ($saturation == 0) {
            return $fromFloat(array($light, $light, $light));
        }

        // Else calculate r, g, b according to hue.
        // Check http://en.wikipedia.org/wiki/HSL_and_HSV#From_HSL for details
        $chroma = (1 - abs(2 * $light - 1)) * $saturation;
        $h_     = $hue * 6;
        $x      = $chroma * (1 - abs((fmod($h_,2)) - 1)); // Note: fmod because % (modulo) returns int value!!
        $m = $light - round($chroma/2, 10); // Bugfix for strange float behaviour (e.g. $l=0.17 and $s=1)

        if ($h_ >= 0 && $h_ < 1) $rgb = array(($chroma + $m), ($x + $m), $m);
        elseif ($h_ >= 1 && $h_ < 2) $rgb = array(($x + $m), ($chroma + $m), $m);
        elseif ($h_ >= 2 && $h_ < 3) $rgb = array($m, ($chroma + $m), ($x + $m));
        elseif ($h_ >= 3 && $h_ < 4) $rgb = array($m, ($x + $m), ($chroma + $m));
        elseif ($h_ >= 4 && $h_ < 5) $rgb = array(($x + $m), $m, ($chroma + $m));
        elseif ($h_ >= 5 && $h_ < 6) $rgb = array(($chroma + $m), $m, ($x + $m));
        else
            throw new \InvalidArgumentException('Invalid hue, it should be a value between 0 and 1.');

        return $fromFloat($rgb);
    }

    /**
     * @param \GdImage $image GD image resource
     * @return int Returns the index of the specified color+alpha in the palette of the image,
     *             or index of allocated color if the color does not exist in the image's palette.
     */
    public function getIndex(\GdImage $image) : int
    {

        $index = $this->hasAlphaChannel()
            ? imagecolorexactalpha(
                $image, $this->red, $this->green, $this->blue, $this->alpha)
            : imagecolorexact(
                $image, $this->red, $this->green, $this->blue);

        if ($index !== -1) {
            return $index;
        }

        return $this->hasAlphaChannel()
            ? imagecolorallocatealpha(
                $image, $this->red, $this->green, $this->blue, $this->alpha)
            : imagecolorallocate(
                $image, $this->red, $this->green, $this->blue);
    }

    /**
     * @return bool TRUE when alpha channel is specified, FALSE otherwise
     */
    public function hasAlphaChannel() : bool
    {
        return $this->alpha !== null;
    }

    /**
     * @return int[]
     */
    public function toArray() : array
    {
        return array($this->red, $this->green, $this->blue);
    }
}