<?php

declare(strict_types=1);

namespace GDText\Struct;

class Rectangle extends Point
{
    /**
     * Rectangle constructor.
     */
    public function __construct(int $x, int $y, private readonly int $width, private readonly int $height)
    {
        parent::__construct($x, $y);
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getLeft(): int
    {
        return $this->getX();
    }

    public function getTop(): int
    {
        return $this->getY();
    }

    public function getRight(): int
    {
        return $this->getX() + $this->width;
    }

    public function getBottom(): int
    {
        return $this->getY() + $this->height;
    }
}