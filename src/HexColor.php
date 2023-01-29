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
        $hex = trim($hex, '#');
        if (strlen($hex) == 3) {
            $hex = $hex.$hex;
        } else if (strlen($hex) == 2) {
            $hex = $hex.$hex.$hex;
        }

        list($r, $g, $b) = sscanf('#'.$hex, "#%02x%02x%02x");

        parent::__construct((int) $r, (int) $g, (int) $b, $alpha);
    }
}
