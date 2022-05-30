<?php

require_once '../Geometry2D.php';
require_once '../Image.php';

use \DantSu\PHPImageEditor\Image;

\header('Content-type: image/png');

Image::fromPath(__DIR__ . '/resources/sample5_base.png')
    ->alphaMask(Image::fromPath(__DIR__ . '/resources/sample5_mask.png'))
    ->crop(494, 494, 9, 12)
    ->setOpacity(0.5)
    ->displayPNG();
