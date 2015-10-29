<?php
namespace GDText\Tests;

class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $name
     * @return resource
     */
    protected function openImageResource($name)
    {
        return imagecreatefromstring(file_get_contents(__DIR__.'/images/'.$name));
    }

    /**
     * @param $name
     * @return string
     */
    protected function sha1ImageResource($name)
    {
        return sha1_file(__DIR__.'/images/'.$name);
    }

    /**
     * @param string $name
     * @param resource $im
     */
    protected function assertImageEquals($name, $im)
    {
        //return imagepng($im, __DIR__.'/images/'.$name);

        ob_start();
        imagepng($im);
        $sha1 = sha1(ob_get_contents());
        ob_end_clean();

        $this->assertEquals($this->sha1ImageResource($name), $sha1);
    }
}