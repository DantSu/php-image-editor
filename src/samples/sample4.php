<?php


require_once '../Geometry2D.php';
require_once '../Image.php';

use \DantSu\PHPImageEditor\Image;

\header('Content-type: image/png');

Image::fromPath(__DIR__ . '/resources/photo.jpg')
    ->downscaleAndCrop(1920, 1080, Image::ALIGN_CENTER, Image::ALIGN_BOTTOM)
    ->drawPolygon([110, 500, 240, 250, 400, 140, 650, 280, 400, 400, 800, 510, 400, 950, 180, 620, 230, 540], '#88229988')
    ->drawCircle(450, 600, 200, '#FFFFFF88')
    ->pasteOn(
        Image::newCanvas(1920, 1080)
            ->drawPolygon([1110, 500, 1240, 250, 1400, 140, 1650, 280, 1400, 400, 1800, 510, 1400, 950, 1180, 620, 1230, 540], '#882299')
            ->drawCircle(1450, 600, 200)
            ->setOpacity(0.6),
        0,
        0
    )
    ->displayPNG();
