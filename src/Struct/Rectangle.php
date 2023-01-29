<?php

namespace GDText\Struct;

class Rectangle extends Point
{
    /**
     * @var int
     */
    private int $width;

    /**
     * @var int
     */
    private int $height;

    /**
     * Rectangle constructor.
     *
     * @param int $x
     * @param int $y
     * @param int $width
     * @param int $height
     */
    public function __construct(int $x, int $y, int $width, int $height)
    {
        parent::__construct($x, $y);
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @return int
     */
    public function getLeft(): int
    {
        return $this->getX();
    }

    /**
     * @return int
     */
    public function getTop(): int
    {
        return $this->getY();
    }

    /**
     * @return int
     */
    public function getRight(): int
    {
        return $this->getX() + $this->width;
    }

    /**
     * @return int
     */
    public function getBottom(): int
    {
        return $this->getY() + $this->height;
    }
}
