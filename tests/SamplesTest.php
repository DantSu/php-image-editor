<?php

namespace DantSu\PHPImageEditor\Tests;

use DantSu\PHPImageEditor\Image;

/**
 * Regression tests for the samples in src/samples.
 *
 * Each test rebuilds the image of one sample (without sending headers or output) and compares it with a golden image
 * that was generated from release 1.4.5. This makes sure that compatibility fixes do not change the rendered output.
 * Additionally, building the image must not raise any PHP deprecation, warning or notice.
 */
final class SamplesTest extends TestCase
{
    private const FONT = self::RESOURCES_DIR . '/font.ttf';

    /**
     * @see src/samples/sample1.php
     */
    public function testSample1CanvasTextCirclesAndArrows(): void
    {
        $this->assertSample('sample1', static function (): Image {
            $image = Image::newCanvas(500, 500)
                ->drawRectangle(0, 0, 500, 500, '#444')
                ->drawRectangle(0, 350, 500, 500, '#FF8800')
                ->writeText('I got the power !', self::FONT, 40, '#FFFFFF', Image::ALIGN_CENTER, 310)
                ->drawCircle(25, 100, 100, '#FF8800')
                ->drawCircle(25, 100, 95, '#000000FF')
                ->drawCircle(475, 100, 100, '#FF8800')
                ->drawCircle(475, 100, 95, '#000000FF');

            for ($i = 0; $i <= 360; $i += 30) {
                $image
                    ->drawArrowWithAngle(250, 200, $i, 80, 2, '#FF8800')
                    ->drawArrowWithAngle(250, 200, ($i + 15), 50, 2, '#FF8800');
            }

            return $image->crop(450, 300);
        });
    }

    /**
     * @see src/samples/sample2.php The sample outputs a JPG; the comparison uses the lossless image instead.
     */
    public function testSample2WatermarkOnPhoto(): void
    {
        $this->assertSample('sample2', static function (): Image {
            return Image::fromPath(self::RESOURCES_DIR . '/photo.jpg')
                ->downscaleAndCrop(1920, 1080, Image::ALIGN_CENTER, Image::ALIGN_BOTTOM)
                ->pasteOn(
                    Image::fromPath(self::RESOURCES_DIR . '/watermark.png')->downscaleProportion(300, 300),
                    Image::ALIGN_RIGHT,
                    Image::ALIGN_TOP
                );
        });
    }

    /**
     * @see src/samples/sample3.php
     */
    public function testSample3CloneAndPaste(): void
    {
        $this->assertSample('sample3', static function (): Image {
            $image = Image::fromPath(self::RESOURCES_DIR . '/photo.jpg')->downscaleAndCrop(512, 1024);

            $image2 = clone $image;
            $image2->drawRectangle(128, 128, 384, 384, '#8822AA');

            return Image::newCanvas(1024, 1024)
                ->pasteOn($image, 0, 0)
                ->pasteOn($image2, 512, 0);
        });
    }

    /**
     * @see src/samples/sample4.php
     */
    public function testSample4PolygonsCirclesAndOpacity(): void
    {
        $this->assertSample('sample4', static function (): Image {
            return Image::fromPath(self::RESOURCES_DIR . '/photo.jpg')
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
                );
        });
    }

    /**
     * @see src/samples/sample5.php
     */
    public function testSample5AlphaMask(): void
    {
        $this->assertSample('sample5', static function (): Image {
            return Image::fromPath(self::RESOURCES_DIR . '/sample5_base.png')
                ->alphaMask(Image::fromPath(self::RESOURCES_DIR . '/sample5_mask.png'))
                ->crop(494, 494, 9, 12)
                ->setOpacity(0.5);
        });
    }

    /**
     * @see src/samples/sample6.php
     */
    public function testSample6TextBoundingBoxWithLetterSpacing(): void
    {
        $this->assertSample('sample6', static function (): Image {
            $image1 = Image::newCanvas(500, 500);
            $bbox = $image1->writeTextAndGetBoundingBox('I got the power !', self::FONT, 40, '#FFFFFF', Image::ALIGN_RIGHT, Image::ALIGN_BOTTOM, Image::ALIGN_RIGHT, Image::ALIGN_BOTTOM, 0, 5);

            return Image::newCanvas(500, 500)
                ->drawPolygon(
                    [
                        $bbox['top-left']['x'], $bbox['top-left']['y'],
                        $bbox['top-right']['x'], $bbox['top-right']['y'],
                        $bbox['bottom-right']['x'], $bbox['bottom-right']['y'],
                        $bbox['bottom-left']['x'], $bbox['bottom-left']['y']
                    ],
                    '00000088'
                )
                ->pasteOn($image1);
        });
    }

    /**
     * Build the image, compare it with the golden image first (so a rendering change is reported even when
     * the code also raises deprecations), then assert that no PHP error was raised.
     */
    private function assertSample(string $name, callable $build): void
    {
        $errors = $this->collectPhpErrors($build, $image);
        $this->assertInstanceOf(Image::class, $image);
        $this->assertImageMatchesGolden($name, $image);
        $this->assertSame([], $errors, 'Expected no PHP deprecations, warnings or notices.');
    }
}
