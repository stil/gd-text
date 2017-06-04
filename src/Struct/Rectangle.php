<?php

namespace GDText\Struct;

class Rectangle extends Point
{
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
        parent::__construct($x, $y);
        $this->width = $width;
        $this->height = $height;
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
        return $this->getX();
    }

    /**
     * @return int
     */
    public function getTop()
    {
        return $this->getY();
    }

    /**
     * @return int
     */
    public function getRight()
    {
        return $this->getX() + $this->width;
    }

    /**
     * @return int
     */
    public function getBottom()
    {
        return $this->getY() + $this->height;
    }
}