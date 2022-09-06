<?php
namespace GDText\Tests;

use GDText\Box;
use GDText\Color;
use GDText\HorizontalAlignment;
use GDText\TextWrapping;
use GDText\VerticalAlignment;

class TextAlignmentTest extends TestCase
{
    protected function mockBox($im)
    {
        imagealphablending($im, true);
        imagesavealpha($im, true);

        $box = new Box($im);
        $box->setFontFace(__DIR__ . '/LinLibertine_R.ttf'); // http://www.dafont.com/franchise.font
        $box->setFontColor(new Color(255, 75, 140));
        $box->setFontSize(16);
        $box->setBackgroundColor(new Color(0, 0, 0));
        $box->setBox(0, 10, imagesx($im), 150);
        return $box;
    }

    public function testAlignment()
    {
        $xList = [HorizontalAlignment::Left, HorizontalAlignment::Center, HorizontalAlignment::Right];
        $yList = [VerticalAlignment::Top, VerticalAlignment::Center, VerticalAlignment::Bottom];

        foreach ($yList as $y) {
            foreach ($xList as $x) {

                $im = $this->openImageResource('owl_png24.png');
                $box = $this->mockBox($im);
                $box->setTextAlign($x, $y);
                $box->draw("Owls are birds from the order Strigiformes, which includes about 200 species.");

                $this->assertImageEquals("test_align_{$y}_{$x}.png", $im);
            }
        }
    }
}
