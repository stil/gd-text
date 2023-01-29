<?php

namespace GDText;

class HexColor extends Color
{
    /**
     * @param string $hex
     * @param int|null $alpha
     */
    public function __construct(string $hex, ?int $alpha = null)
    {
        $hex = '#'.trim($hex, '#');
        list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");

        parent::__construct($r, $g, $b, $alpha);
    }
}
