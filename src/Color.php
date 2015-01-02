<?php
namespace GDText;

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
     * @param resource $image GD image resource
     * @return int Returns the index of the specified color+alpha in the palette of the image,
     *             or -1 if the color does not exist in the image's palette.
     */
    public function getIndex($image)
    {
        if ($this->hasAlphaChannel()) {
            return imagecolorexactalpha(
                $image,
                $this->red,
                $this->green,
                $this->blue,
                $this->alpha
            );
        } else {
            return imagecolorexact(
                $image,
                $this->red,
                $this->green,
                $this->blue
            );
        }
    }

    /**
     * @return bool TRUE when alpha channel is specified, FALSE otherwise
     */
    public function hasAlphaChannel()
    {
        return $this->alpha !== null;
    }
}