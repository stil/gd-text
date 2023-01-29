# gd-text

forked from [stil/gd-text](https://github.com/stil/gd-text)

## Installation via Composer:

```bash
composer require norman-huth/gd-text
```

## Basic usage example

### New Fork Features

#### HexColor

```php
use GDText\HexColor;

$box->setFontColor(new HexColor('#0ea5e9'));
$box->setFontColor(new HexColor('7e22ce'));
$box->setFontColor(new HexColor('7e22ce', 50));
```

#### [Tailwind CSS Color](https://tailwindcss.com/docs/customizing-colors)

```php
use GDText\TailwindColor;

$box->setFontColor(new TailwindColor('slate', 200));
$box->setFontColor(new TailwindColor('slate', 200, 50));
```

### Usage

```php
<?php
require __DIR__.'/../vendor/autoload.php';

use GDText\Box;
use GDText\Color;

$image = imagecreatetruecolor(500, 500);
$backgroundColor = imagecolorallocate($image, 0, 18, 64);
imagefill($image, 0, 0, $backgroundColor);

$box = new Box($image);
$box->setFontFace(__DIR__.'/Franchise-Bold-hinted.ttf'); // https://www.dafont.com/franchise.font
$box->setFontColor(new Color(255, 75, 140));
$box->setTextShadow(new Color(0, 0, 0, 50), 2, 2);
$box->setFontSize(40);
$box->setBox(20, 20, 460, 460);
$box->setTextAlign('left', 'top');
$box->draw('Franchise\nBold');

$box = new Box($image);
$box->setFontFace(__DIR__.'/Pacifico.ttf'); // https://www.dafont.com/pacifico.font
$box->setFontSize(80);
$box->setFontColor(new Color(255, 255, 255));
$box->setTextShadow(new Color(0, 0, 0, 50), 0, -2);
$box->setBox(20, 20, 460, 460);
$box->setTextAlign('center', 'center');
$box->draw('Pacifico');

$box = new Box($image);
$box->setFontFace(__DIR__.'/Prisma.otf'); // https://www.dafont.com/prisma.font
$box->setFontSize(70);
$box->setFontColor(new Color(148, 212, 1));
$box->setTextShadow(new Color(0, 0, 0, 50), 0, -2);
$box->setBox(20, 20, 460, 460);
$box->setTextAlign('right', 'bottom');
$box->draw('Prisma');

header('Content-type: image/png');
imagepng($image);
```

#### Example output:

![fonts example](https://raw.githubusercontent.com/Muetze42/gd-text/main/examples/fonts.png)

#### Multi lined text

```php
<?php
require __DIR__.'/../vendor/autoload.php';

use GDText\Box;
use GDText\Color;

$image = imagecreatetruecolor(500, 500);
$backgroundColor = imagecolorallocate($image, 0, 18, 64);
imagefill($image, 0, 0, $backgroundColor);

$box = new Box($image);
$box->setFontFace(__DIR__.'/Minecraftia.ttf'); // https://www.dafont.com/minecraftia.font
$box->setFontColor(new Color(255, 75, 140));
$box->setTextShadow(new Color(0, 0, 0, 50), 2, 2);
$box->setFontSize(8);
$box->setLineHeight(1.5);
//$box->enableDebug();
$box->setBox(20, 20, 460, 460);
$box->setTextAlign('left', 'top');
$box->draw(
    '    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla eleifend congue auctor. Nullam eget blandit magna. Fusce posuere lacus at orci blandit auctor. Aliquam erat volutpat. Cras pharetra aliquet leo. Cras tristique tellus sit amet vestibulum ullamcorper. Aenean quam erat, ullamcorper quis blandit id, sollicitudin lobortis orci. In non varius metus. Aenean varius porttitor augue, sit amet suscipit est posuere a. In mi leo, fermentum nec diam ut, lacinia laoreet enim. Fusce augue justo, tristique at elit ultricies, tincidunt bibendum erat.\n\n    Aenean feugiat dignissim dui non scelerisque. Cras vitae rhoncus sapien. Suspendisse sed ante elit. Duis id dolor metus. Vivamus congue metus nunc, ut consequat arcu dapibus vel. Ut sed ipsum sollicitudin, rutrum quam ac, fringilla risus. Phasellus non tincidunt leo, sodales venenatis nisl. Duis lorem odio, porta quis laoreet ut, tristique a justo. Morbi dictum dictum est ut facilisis. Duis suscipit sem ligula, at commodo risus pulvinar vehicula. Sed quis quam ac quam scelerisque dapibus id non justo. Sed mollis enim id neque tempus, a congue nulla blandit. Aliquam congue convallis lacinia. Aliquam commodo eleifend nisl a consectetur.\n\n    Maecenas sem nisl, adipiscing nec ante sed, sodales facilisis lectus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Ut bibendum malesuada ipsum eget vestibulum. Pellentesque interdum tempor libero eu sagittis. Suspendisse luctus nisi ante, eget tempus erat tristique sed. Duis nec pretium velit. Praesent ornare, tortor non sagittis sollicitudin, dolor quam scelerisque risus, eu consequat magna tellus id diam. Fusce auctor ultricies arcu, vel ullamcorper dui condimentum nec. Maecenas tempus, odio non ullamcorper dignissim, tellus eros elementum turpis, quis luctus ante libero et nisi.\n\n    Phasellus sed mauris vel lorem tristique tempor. Pellentesque ornare purus quis ullamcorper fermentum. Curabitur tortor mauris, semper ut erat vitae, venenatis congue eros. Ut imperdiet arcu risus, id dapibus lacus bibendum posuere. Etiam ac volutpat lectus. Vivamus in magna accumsan, dictum erat in, vehicula sem. Donec elementum lacinia fringilla. Vivamus luctus felis quis sollicitudin eleifend. Sed elementum, mi et interdum facilisis, nunc eros suscipit leo, eget convallis arcu nunc eget lectus. Quisque bibendum urna sit amet varius aliquam. In mollis ante sit amet luctus tincidunt.'
);

header('Content-type: image/png;');
imagepng($image, null, 9, PNG_ALL_FILTERS);
```

#### Text stroke

```php
<?php
require __DIR__.'/../vendor/autoload.php';

use GDText\Box;
use GDText\Color;

$image = imagecreatetruecolor(500, 500);
$backgroundColor = imagecolorallocate($image, 0, 18, 64);
imagefill($image, 0, 0, $backgroundColor);

$box = new Box($image);
$box->setFontFace(__DIR__.'/Elevant bold.ttf'); // https://www.dafont.com/elevant-by-pelash.font
$box->setFontSize(150);
$box->setFontColor(new Color(255, 255, 255));
$box->setBox(15, 20, 460, 460);
$box->setTextAlign('center', 'center');

$box->setStrokeColor(new Color(255, 75, 140)); // Set stroke color
$box->setStrokeSize(3); // Stroke size in pixels

$box->draw('Elevant');

header("Content-type: image/png;");
imagepng($image, null, 9, PNG_ALL_FILTERS);
```

#### Text background

```php
<?php
require __DIR__.'/../vendor/autoload.php';

use GDText\Box;
use GDText\Color;

$image = imagecreatetruecolor(500, 500);
$backgroundColor = imagecolorallocate($image, 0, 18, 64);
imagefill($image, 0, 0, $backgroundColor);

$box = new Box($image);
$box->setFontFace(__DIR__.'/fonts/BebasNeue.otf'); // https://www.dafont.com/elevant-by-pelash.font
$box->setFontSize(100);
$box->setFontColor(new Color(255, 255, 255));
$box->setBox(15, 20, 460, 460);
$box->setTextAlign('center', 'center');

$box->setBackgroundColor(new Color(255, 86, 77));

$box->draw("Bebas Neue");

header('Content-type: image/png;');
imagepng($image, null, 9, PNG_ALL_FILTERS);
```

### Demos

#### Line height demo:

![line height example](https://raw.githubusercontent.com/Muetze42/gd-text/main/examples/lineheight.gif)

#### Text alignment demo:

![align example](https://raw.githubusercontent.com/Muetze42/gd-text/main/examples/alignment.gif)

#### Text stroke demo:

![stroke example](https://raw.githubusercontent.com/Muetze42/gd-text/main/examples/stroke.gif)

#### Text background demo:

![stroke example](https://raw.githubusercontent.com/Muetze42/gd-text/main/examples/background.gif)

#### Debug mode enabled demo:

![debug example](https://raw.githubusercontent.com/Muetze42/gd-text/main/examples/debug.png)

#### A Laravel Usage Example:

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use GDText\Box;
use GDText\TailwindColor;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RecipeController extends Controller
{
    /**
     * @param Recipe $recipe
     * @return StreamedResponse
     */
    public function image(Recipe $recipe)
    {
        return response()->stream(function () use ($recipe) {
            $baseImage = resource_path('assets/open-graph/recipe-show.jpg');        // 1200 x 630

            $image = imagecreatefromjpeg($baseImage);

            $x = 30;
            $y = 420;
            $shift = 3;

            $box = new Box($image);
            $box->setFontFace(resource_path('fonts/Pacifico.ttf'));
            $box->setFontColor(new TailwindColor('slate', 200));
            $box->setTextShadow(new TailwindColor('neutral', 500, 50), $shift, $shift);
            $box->setFontSize(60);
            $box->setBox($x, $y, 1200 - (2 * $x), 630);
            $box->setTextAlign('center', 'top');
            $box->draw($recipe->title);

            $content = imagejpeg($image, null, 100);
            imagedestroy($image);

            echo $content;
        }, 200, [
            'Content-Type' => 'image/jpeg',
        ]);
    }
}
```

[![Laravel example](https://raw.githubusercontent.com/Muetze42/gd-text/main/examples/laravel.jpg)](https://halexa.tv/)
