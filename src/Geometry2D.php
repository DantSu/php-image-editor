<?php

namespace DantSu\PHPImageEditor;

class Geometry2D
{
    public static function degrees0to360($angle)
    {
        while ($angle < 0 || $angle >= 360) {
            if ($angle < 0) $angle += 360;
            elseif ($angle >= 360) $angle -= 360;
        }
        return $angle;
    }

    public static function getDstXY($originX, $originY, $angle, $length)
    {
        $angle = 360 - $angle;
        return [
            'x' => $originX + \cos($angle * M_PI / 180) * $length,
            'y' => $originY + \sin($angle * M_PI / 180) * $length
        ];
    }

    public static function getDstXYRounded($originX, $originY, $angle, $length)
    {
        $xy = Geometry2D::getDstXY($originX, $originY, $angle, $length);
        return [
            'x' => \round($xy['x']),
            'y' => \round($xy['y'])
        ];
    }

    public static function getAngleAndLengthFromPoints($originX, $originY, $dstX, $dstY)
    {
        $width = $dstX - $originX;
        $height = $dstY - $originY;
        $diameter = \sqrt(\pow($width, 2) + \pow($height, 2));

        if($width == 0) {
            $angle = 90;
        } elseif ($height == 0) {
            $angle = 0;
        } else {
            $angle = \atan2(\abs($height), \abs($width)) * 180.0 / M_PI;
        }

        if($width < 0 && $height < 0) {
            $angle += 180;
        } elseif ($width < 0) {
            $angle = 180 - $angle;
        } elseif ($height < 0) {
            $angle = 360 - $angle;
        }

        return [
            'angle' => 360 - $angle,
            'length' => $diameter
        ];
    }
}
