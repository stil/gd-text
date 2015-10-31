<?php
namespace GDText\Tests;

use GDText\Color;

class ColorTest extends TestCase
{
    public function testPaletteImage()
    {
        $im = $this->openImageResource('owl.gif');

        $color = new Color(0, 0, 255);

        $index = $color->getIndex($im);
        $this->assertNotEquals(-1, $index);
    }

    public function testTrueColorImage()
    {
        $im = $this->openImageResource('owl_png24.png');

        $color = new Color(0, 0, 255);

        $index = $color->getIndex($im);
        $this->assertNotEquals(-1, $index);

        $im = imagecreatetruecolor(1, 1);

        $index = $color->getIndex($im);
        $this->assertNotEquals(-1, $index);
    }

    public function testToArray()
    {
        $color = new Color(12, 34, 56);
        $this->assertEquals(array(12, 34, 56), $color->toArray());
    }

    public function testFromHsl()
    {
        $table = [
            [[0.5, 0.8, 0.3], [15, 138, 138]],
            [[0.999, 1, 1], [255, 255, 255]],
            [[0, 0, 0], [0, 0, 0]],
            [[338/360, 0.85, 0.25], [118, 10, 49]],
        ];

        foreach ($table as $pair) {
            list($hsl, $rgb) = $pair;
            $color = Color::fromHsl($hsl[0], $hsl[1], $hsl[2]);

            $this->assertEquals($rgb, $color->toArray());
        }
    }

    public function testParseString()
    {
        $table = [
            ['#000', [0, 0, 0]],
            ['#fff', [255, 255, 255]],
            ['#abcdef', [171, 205, 239]],
            ['#FEDCBA', [254, 220, 186]],
            ['FEDCBA', [254, 220, 186]],
            ['#abc', [170, 187, 204]],
            ['abc', [170, 187, 204]],
        ];

        foreach ($table as $pair) {
            $color = Color::parseString($pair[0]);
            $this->assertEquals($pair[1], $color->toArray());
        }
    }
}
