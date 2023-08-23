<?php

require_once '../Geometry2D.php';
require_once '../Image.php';

use \DantSu\PHPImageEditor\Image;

\header('Content-type: image/png');

$image1 = Image::newCanvas(500, 500);
$bbox = $image1->writeTextAndGetBoundingBox('I got the power !', __DIR__ . '/resources/font.ttf', 40, '#FFFFFF', Image::ALIGN_RIGHT, Image::ALIGN_BOTTOM, Image::ALIGN_RIGHT, Image::ALIGN_BOTTOM, 0, 5);

Image::newCanvas(500, 500)
    ->drawPolygon(
        [
            $bbox['top-left']['x'], $bbox['top-left']['y'],
            $bbox['top-right']['x'], $bbox['top-right']['y'],
            $bbox['bottom-right']['x'], $bbox['bottom-right']['y'],
            $bbox['bottom-left']['x'], $bbox['bottom-left']['y']
        ],
        '00000088'
    )
    ->pasteOn($image1)
    ->displayPNG();
