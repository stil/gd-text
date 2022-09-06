<?php

namespace GDText\Struct;

class Rectangle extends Point
{
    private int $width;
    private int $height;

    public function __construct(float $x, float $y, float $width, float $height)
    {
        parent::__construct((int)$x, (int)$y);

        $this->width = (int)$width;
        $this->height = (int)$height;
    }

    public function getWidth() : int
    {
        return $this->width;
    }

    public function getHeight() : int
    {
        return $this->height;
    }

    public function getLeft() : int
    {
        return $this->getX();
    }

    public function getTop() : int
    {
        return $this->getY();
    }

    public function getRight() : int
    {
        return $this->getX() + $this->width;
    }

    public function getBottom() : int
    {
        return $this->getY() + $this->height;
    }
}