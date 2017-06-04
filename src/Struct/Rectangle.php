<?php

namespace GDText\Struct;

class Rectangle
{
    /**
     * @var int
     */
    private $x;

    /**
     * @var int
     */
    private $y;

    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    /**
     * Rectangle constructor.
     * @param int $x
     * @param int $y
     * @param int $width
     * @param int $height
     */
    public function __construct($x, $y, $width, $height)
    {
        $this->x = $x;
        $this->y = $y;
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * @return int
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @return int
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return int
     */
    public function getLeft()
    {
        return $this->x;
    }

    /**
     * @return int
     */
    public function getTop()
    {
        return $this->y;
    }

    /**
     * @return int
     */
    public function getRight()
    {
        return $this->x + $this->width;
    }

    /**
     * @return int
     */
    public function getBottom()
    {
        return $this->y + $this->height;
    }
}