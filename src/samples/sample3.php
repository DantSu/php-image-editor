<?php

require_once '../Geometry2D.php';
require_once '../Image.php';

use \DantSu\PHPImageEditor\Image;

\header('Content-type: image/png');

$image = Image::fromPath(__DIR__ . '/resources/photo.jpg')->downscaleAndCrop(512, 1024);

$image2 = clone $image;
$image2->drawRectangle(128, 128, 384, 384, '#8822AA');

Image::newCanvas(1024, 1024)
    ->pasteOn($image, 0, 0)
    ->pasteOn($image2, 512, 0)
    ->displayPNG();

