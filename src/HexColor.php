<?php

namespace GDText;

use InvalidArgumentException;

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

        if (strlen($hex) != 6) {
            throw new InvalidArgumentException('Unrecognized color');
        }

        list($r, $g, $b) = sscanf('#'.$hex, '#%02x%02x%02x');

        parent::__construct((int) $r, (int) $g, (int) $b, $alpha);
    }
}
