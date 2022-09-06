<?php

namespace GDText\Struct;

use GDText\Color;

class Shadow
{
    public Color $color;
    public Point $offset;

    public function __construct(Color $color, Point $point)
    {
        $this->color = $color;
        $this->offset = $point;
    }
}