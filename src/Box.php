<?php
namespace GDText;

use GDText\Struct\Point;
use GDText\Struct\Rectangle;

class Box
{
    protected \GdImage $im;

    protected int $strokeSize = 0;
    protected Color $strokeColor;
    protected int $fontSize = 12;
    protected Color $fontColor;
    protected string $alignX = 'left';
    protected string $alignY = 'top';
    protected int $textWrapping = TextWrapping::WrapWithOverflow;
    protected float $lineHeight = 1.25;
    protected float $baseline = 0.2;
    protected ?string $fontFaceFilePath = null;
    protected bool $debug = false;

    protected ?Struct\Shadow $textShadow = null;

    protected ?Color $backgroundColor = null;
    protected Struct\Rectangle $box;

    public function __construct(\GDImage $image)
    {
        $this->im = $image;
        $this->fontColor = new Color(0, 0, 0);
        $this->strokeColor = new Color(0, 0, 0);
        $this->box = new Rectangle(0, 0, 100, 100);
    }

    public function setFontColor(Color $fontColor) : void
    {
        $this->fontColor = $fontColor;
    }

    /**
     * @param string $filePath Path to the font file
     */
    public function setFontFace(string $filePath) : void
    {
        $this->fontFaceFilePath = $filePath;
    }

    /**
     * @param int $fontsizePixels Font size in *pixels*
     */
    public function setFontSize(int $fontsizePixels) : void
    {
        $this->fontSize = $fontsizePixels;
    }
    
    public function setStrokeColor(Color $strokeColor) : void
    {
        $this->strokeColor = $strokeColor;
    }

    /**
     * @param int $strokeSizePixels Stroke size in *pixels*
     */
    public function setStrokeSize(int $strokeSizePixels) : void
    {
        $this->strokeSize = $strokeSizePixels;
    }

    /**
     * @param Color $color Shadow color
     * @param int $xShift Relative shadow position in pixels. Positive values move shadow to right, negative to left.
     * @param int $yShift Relative shadow position in pixels. Positive values move shadow to bottom, negative to up.
     */
    public function setTextShadow(Color $color, int $xShift, int $yShift) : void
    {
        $this->textShadow = new Struct\Shadow(
            $color,
            new Struct\Point($xShift, $yShift),
        );
    }

    public function setBackgroundColor(Color $color) : void
    {
        $this->backgroundColor = $color;
    }

    /**
     * Allows to customize spacing between lines.
     * @param float $value Height of the single text line, in percents, proportionally to font size
     */
    public function setLineHeight(float $value) : void
    {
        $this->lineHeight = $value;
    }

    /**
     * @param float $value Position of baseline, in percents, proportionally to line height measuring from the bottom.
     */
    public function setBaseline(float $value) : void
    {
        $this->baseline = $value;
    }

    /**
     * Sets text alignment inside textbox
     * @param string $x Horizontal alignment. Allowed values are: left, center, right.
     * @param string $y Vertical alignment. Allowed values are: top, center, bottom.
     */
    public function setTextAlign(string $x = 'left', string $y = 'top') : void
    {
        $xAllowed = array('left', 'right', 'center');
        $yAllowed = array('top', 'bottom', 'center');

        if (!in_array($x, $xAllowed)) {
            throw new \InvalidArgumentException('Invalid horizontal alignement value was specified.');
        }

        if (!in_array($y, $yAllowed)) {
            throw new \InvalidArgumentException('Invalid vertical alignement value was specified.');
        }

        $this->alignX = $x;
        $this->alignY = $y;
    }

    /**
     * Sets textbox position and dimensions
     * @param int $x Distance in pixels from left edge of image.
     * @param int $y Distance in pixels from top edge of image.
     * @param int $width Width of texbox in pixels.
     * @param int $height Height of textbox in pixels.
     */
    public function setBox(int $x, int $y, int $width, int $height) : void
    {
        $this->box = new Rectangle($x, $y, $width, $height);
    }

    /**
     * Enables debug mode. Whole textbox and individual lines will be filled with random colors.
     */
    public function enableDebug() : void
    {
        $this->debug = true;
    }

    public function setTextWrapping(int $textWrapping) : void
    {
        $this->textWrapping = $textWrapping;
    }


    /**
     * Draws the text on the picture.
     *
     * @param string $text Text to draw. May contain newline characters.
     *
     * @return Rectangle Area that cover the drawn text
     */
    public function draw(string $text) : Struct\Rectangle
    {
        return $this->drawText($text, true);
    }

    /**
     * Draws the text on the picture, fitting it to the current box
     *
     * @param string $text      Text to draw. May contain newline characters.
     * @param int    $precision Increment or decrement of font size. The lower this value, the slower this method.
     *
     * @return Rectangle Area that cover the drawn text
     */
    public function drawFitFontSize(string $text, int $precision = -1, int $maxFontSize = -1, int $minFontSize = -1, &$usedFontSize = null)  : Struct\Rectangle
    {
        $initialFontSize = $this->fontSize;

        $usedFontSize = $this->fontSize;
        $rectangle = $this->calculate($text);

        if ($rectangle->getHeight() > $this->box->getHeight() || $rectangle->getWidth() > $this->box->getWidth()) {
            // Decrement font size
            do {
                $this->setFontSize($usedFontSize);
                $rectangle = $this->calculate($text);

                $usedFontSize -= $precision;
            } while (
                ($minFontSize == -1 || $usedFontSize > $minFontSize) &&
                ($rectangle->getHeight() > $this->box->getHeight() || $rectangle->getWidth() > $this->box->getWidth())
            );

            $usedFontSize += $precision;
        } else {
            // Increment font size
            do {
                $this->setFontSize($usedFontSize);
                $rectangle = $this->calculate($text);

                $usedFontSize += $precision;
            } while (($maxFontSize > 0 && $usedFontSize < $maxFontSize)
            && $rectangle->getHeight() < $this->box->getHeight()
            && $rectangle->getWidth() < $this->box->getWidth());

            $usedFontSize -= $precision * 2;
        }
        $this->setFontSize($usedFontSize);

        $rectangle = $this->drawText($text, true);

        // Restore initial font size
        $this->setFontSize($initialFontSize);

        return $rectangle;
    }

    /**
     * Get the area that will cover the given text
     */
    public function calculate(string $text) : Struct\Rectangle
    {
        return $this->drawText($text, false);
    }

    /**
     * Draws the text on the picture.
     * @param string $text Text to draw. May contain newline characters.
     * @param bool $draw
     * @return Rectangle
     */
    protected function drawText(string $text, bool $draw) : Struct\Rectangle
    {
        if (!isset($this->fontFaceFilePath)) {
            throw new \InvalidArgumentException('No path to font file has been specified.');
        }

        $lines = match ( $this->textWrapping ) {
            TextWrapping::NoWrap => array( $text ),
            default => $this->wrapTextWithOverflow($text),
        };

        if ($this->debug) {
            // Marks whole textbox area with color
            $this->drawFilledRectangle(
                $this->box,
                new Color(rand(180, 255), rand(180, 255), rand(180, 255), 80)
            );
        }

        $lineHeightPx = $this->lineHeight * $this->fontSize;
        $textHeight = count($lines) * $lineHeightPx;

        $yAlign = match ( $this->alignY ) {
            VerticalAlignment::Center => ( $this->box->getHeight() / 2 ) - ( $textHeight / 2 ),
            VerticalAlignment::Bottom => $this->box->getHeight() - $textHeight,
            default => 0,
        };

        $n = 0;

        $drawnX = $drawnY = PHP_INT_MAX;
        $drawnH = $drawnW = 0;

        foreach ($lines as $line) {
            $box = $this->calculateBox($line);

            $xAlign = match ( $this->alignX ) {
                HorizontalAlignment::Center => ( $this->box->getWidth() - $box->getWidth() ) / 2,
                HorizontalAlignment::Right => ( $this->box->getWidth() - $box->getWidth() ),
                default => 0,
            };

            $yShift = $lineHeightPx * (1 - $this->baseline);

            // current line X and Y position
            $xMOD = $this->box->getX() + $xAlign;
            $yMOD = $this->box->getY() + $yAlign + $yShift + ($n * $lineHeightPx);

            if ($draw && $line && $this->backgroundColor !== null) {
                // Marks whole textbox area with given background-color
                $backgroundHeight = $this->fontSize;

                $this->drawFilledRectangle(
                    new Rectangle(
                        $xMOD,
                        $this->box->getY() + $yAlign + ($n * $lineHeightPx) + ($lineHeightPx - $backgroundHeight) + (1 - $this->lineHeight) * 13 * (1 / 50 * $this->fontSize),
                        $box->getWidth(),
                        $backgroundHeight
                    ),
                    $this->backgroundColor
                );
            }

            if ($this->debug) {
                // Marks current line with color
                $this->drawFilledRectangle(
                    new Rectangle(
                        $xMOD,
                        $this->box->getY() + $yAlign + ($n * $lineHeightPx),
                        $box->getWidth(),
                        $lineHeightPx
                    ),
                    new Color(rand(1, 180), rand(1, 180), rand(1, 180))
                );
            }

            if ($draw) {
                if ($this->textShadow !== null) {
                    $this->drawInternal(
                        new Point(
                            $xMOD + $this->textShadow->offset->getX(),
                            $yMOD + $this->textShadow->offset->getY()
                        ),
                        $this->textShadow->color,
                        $line,
                    );
                }

                $this->strokeText((int)$xMOD, (int)$yMOD, $line);
                $this->drawInternal(
                    new Point(
                        (int)$xMOD,
                        (int)$yMOD
                    ),
                    $this->fontColor,
                    $line,
                );
            }

            $drawnX = min($xMOD, $drawnX);
            $drawnY = min($this->box->getY() + $yAlign + ($n * $lineHeightPx), $drawnY);
            $drawnW = max($drawnW, $box->getWidth());
            $drawnH += $lineHeightPx;

            $n++;
        }

        return new Rectangle($drawnX, $drawnY, $drawnW, $drawnH);
    }

    /**
     * Splits overflowing text into array of strings.
     * @param string $text
     * @return string[]
     */
    protected function wrapTextWithOverflow(string $text) : array
    {
        $lines = array();
        // Split text explicitly into lines by \n, \r\n and \r
        $explicitLines = preg_split('/\n|\r\n?/', $text);
        foreach ($explicitLines as $line) {
            // Check every line if it needs to be wrapped
            $words = explode(" ", $line);
            $line = $words[0];
            for ($i = 1; $i < count($words); $i++) {
                $box = $this->calculateBox($line." ".$words[$i]);
                if ($box->getWidth() >= $this->box->getWidth()) {
                    $lines[] = $line;
                    $line = $words[$i];
                } else {
                    $line .= " ".$words[$i];
                }
            }
            $lines[] = $line;
        }
        return $lines;
    }

    protected function getFontSizeInPoints() : float
    {
        return 0.75 * $this->fontSize;
    }

    protected function drawFilledRectangle(Rectangle $rect, Color $color) : void
    {
        imagefilledrectangle(
            $this->im,
            $rect->getLeft(),
            $rect->getTop(),
            $rect->getRight(),
            $rect->getBottom(),
            $color->getIndex($this->im)
        );
    }

    /**
     * Returns the bounding box of a text.
     */
    protected function calculateBox(string $text) : Struct\Rectangle
    {
        $bounds = imagettfbbox($this->getFontSizeInPoints(), 0, $this->fontFaceFilePath, $text);

        $xLeft  = $bounds[0]; // (lower|upper) left corner, X position
        $xRight = $bounds[2]; // (lower|upper) right corner, X position
        $yLower = $bounds[1]; // lower (left|right) corner, Y position
        $yUpper = $bounds[5]; // upper (left|right) corner, Y position

        return new Rectangle(
            $xLeft,
            $yUpper,
            $xRight - $xLeft,
            $yLower - $yUpper
        );
    }

    protected function strokeText(int $x, int $y, string $text) : void
    {
        $size = $this->strokeSize;
        if ($size <= 0) return;
        for ($c1 = $x - $size; $c1 <= $x + $size; $c1++) {
            for ($c2 = $y - $size; $c2 <= $y + $size; $c2++) {
                $this->drawInternal(new Point($c1, $c2), $this->strokeColor, $text);
            }
        }
    }

    protected function drawInternal(Point $position, Color $color, $text) : void
    {
        imagettftext(
            $this->im,
            $this->getFontSizeInPoints(),
            0, // no rotation
            $position->getX(),
            $position->getY(),
            $color->getIndex($this->im),
            $this->fontFaceFilePath,
            $text
        );
    }
}