<?php

namespace GDText\Tests;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function openImageResource(string $name): \GdImage
    {
        return imagecreatefromstring(file_get_contents(__DIR__ . '/images/' . $name));
    }

    protected function sha1ImageResource(string $name): string
    {
        return sha1_file(__DIR__ . '/images/' . $name);
    }

    protected function assertImageEquals(string $name, \GdImage $im): void
    {
        //return imagepng($im, __DIR__.'/images/'.$name);

        ob_start();
        imagepng($im);
        $sha1 = sha1(ob_get_contents());
        ob_end_clean();

        $this->assertEquals($this->sha1ImageResource($name), $sha1);
    }
}