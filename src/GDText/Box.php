<?php
namespace GDText;

class Box
{
    public function __construct(&$image)
    {
        $this->im = $image;
        
        $this->fontColor     = $this->getColorIndex(array(0, 0, 0)); // setFontColor()
        $this->fontSize      = 10; // setFontSize()
        $this->textShadow    = false; // setTextShadow()
        $this->debug         = false; // enableDebug()
        $this->alignX        = 'left'; // setTextAlign()
        $this->alignY        = 'top'; // setTextAlign()
        $this->lineHeight    = 1.5; // setLineHeight()
        $this->baseline      = 0.75; // setBaseline()
        $this->leading       = 1; // setLeading()
        $this->box['x']      = 0; // setBox()
        $this->box['y']      = 0;
        $this->box['width']  = 100;
        $this->box['height'] = 100;
    }
    
    protected function getColorIndex($v)
    {
        if (count($v) == 3) {
            $color = imagecolorexact($this->im, $v[0], $v[1], $v[2]);
        } elseif (count($v) == 4) {
            $color = imagecolorexactalpha($this->im, $v[0], $v[1], $v[2], $v[3]);
        }
        return $color;
    }
    
    public function setFontColor($v)
    {
        $this->fontColor = $this->getColorIndex($v);
    }
    
    public function setFontFace($v)
    {
        $this->fontFace = $v;
    }
    
    public function setLeading($v)
    {
        $this->leading = $v;
    }
    
    public function setFontSize($v)
    {
        $this->fontSize = $v;
    }
    
    public function setTextShadow($color, $x, $y)
    {
        $this->textShadow['color'] = $this->getColorIndex($color);
        $this->textShadow['x'] = $x;
        $this->textShadow['y'] = $y;
    }
    
    public function setLineHeight($v)
    {
        $this->lineHeight = $v;
    }
    
    public function setBaseline($v)
    {
        $this->baseline = $v;
    }
    
    public function setTextAlign($x = 'left', $y = 'top')
    {
        $xAllowed = array('left', 'right', 'center');
        $yAllowed = array('top', 'bottom', 'center');
        if (in_array($x, $xAllowed)) {
            $this->alignX = $x;
        }
        if (in_array($y, $yAllowed)) {
            $this->alignY = $y;
        }
    }
    
    public function setBox($x, $y, $width, $height)
    {
        $this->box['x'] = $x;
        $this->box['y'] = $y;
        $this->box['width'] = $width;
        $this->box['height'] = $height;
    }
    
    public function enableDebug()
    {
        $this->debug = true;
    }
    
    public function draw($text)
    {
        //debug_print_backtrace();
        $lines = explode("\n", $text);
        
        $this->lineHeight = $this->lineHeight*$this->fontSize;
        $this->leading = ($this->leading*$this->lineHeight)-$this->lineHeight;
        
        // If the line has no new line chars then wrap it.
        if(count($lines) == 1) {
            unset($lines[0]);
            $words = explode(" ", $text);
            $line = $words[0];
            for($i = 1; $i < count($words); $i++) {
                
                $box = imageftbbox($this->fontSize, 0, $this->fontFace, $line." ".$words[$i]);
                
                if(($box[4]-$box[6]) >= $this->box['width']) {
                    $lines[] = $line;
                    $line = $words[$i];
                }
                else {
                    $line .= " ".$words[$i];
                }
            }
            $lines[] = $line; 
        }
        

        if ($this->debug==true) {
            imagefilledrectangle(
                $this->im,
                $this->box['x'],
                $this->box['y'],
                $this->box['x']+$this->box['width'],
                $this->box['y']+$this->box['height'],
                imagecolorallocatealpha($this->im, rand(180, 255), rand(180, 255), rand(180, 255), 80)
            );
        }
        
        $textHeight = count($lines)*$this->lineHeight;
        
        switch ($this->alignY) {
            case 'top':
                $yAlign = 0;
                break;
            case 'center':
                $yAlign = ($this->box['height']/2)-($textHeight/2);
                break;
            case 'bottom':
                $yAlign = $this->box['height']-$textHeight;
                break;
        }
        
        $n=0;
        foreach ($lines as $line) {
            $box = imageftbbox($this->fontSize, 0, $this->fontFace, $line);
            switch ($this->alignX) {
                case 'left':
                    $xAlign = 0;
                    break;
                case 'center':
                    $xAlign = ($this->box['width']-($box[2]-$box[0]))/2;
                    break;
                case 'right':
                    $xAlign = ($this->box['width']-($box[2]-$box[0]));
                    break;
            }
            $yShift = $this->lineHeight*$this->baseline;
            $xMOD = $this->box['x']+$xAlign;
            $yMOD = $this->box['y']+$yAlign+$yShift+($n*$this->lineHeight)+($n*$this->leading);
            
            if ($this->debug == true) {
                imagefilledrectangle(
                    $this->im,
                    $xMOD,
                    $this->box['y']+$yAlign+($n*$this->lineHeight)+($n*$this->leading),
                    $xMOD+($box[2]-$box[0]),
                    $this->box['y']+$yAlign+($n*$this->lineHeight)+($n*$this->leading)+$this->lineHeight,
                    $this->getColorIndex(array(rand(1, 180),rand(1, 180),rand(1, 180)))
                );
            }
            
            if ($this->textShadow !== false) {
                imagefttext(
                    $this->im,
                    $this->fontSize,
                    0,
                    $xMOD+$this->textShadow['x'],
                    $yMOD+$this->textShadow['y'],
                    $this->textShadow['color'],
                    $this->fontFace,
                    $line
                );
            }
            
            imagefttext(
                $this->im,
                $this->fontSize,
                0,
                $xMOD,
                $yMOD,
                $this->fontColor,
                $this->fontFace,
                $line
            );
            $n++;
        }
    }
}
