<?php

namespace DantSu\PHPImageEditor\Tests;

use DantSu\PHPImageEditor\Image;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Drawing operations, mainly edge cases that raise errors on PHP 8.
 */
final class DrawingTest extends TestCase
{
    private const FONT = self::RESOURCES_DIR . '/font.ttf';

    public function testDrawPolygonWithThreePointsFillsArea(): void
    {
        $image = $this->assertNoPhpErrors(
            static fn() => Image::newCanvas(10, 10)->drawPolygon([0, 0, 9, 0, 0, 9], '#FF0000')
        );

        $this->assertSame(0xFF0000, \imagecolorat($image->getImage(), 1, 1) & 0xFFFFFF);
    }

    /**
     * @return array<string, array{int[], bool}>
     */
    public static function polygonsWithTooFewPoints(): array
    {
        return [
            'no point' => [[], false],
            'one point' => [[1, 1], false],
            'two points' => [[1, 1, 8, 8], false],
            'two points with antialias' => [[1, 1, 8, 8], true],
        ];
    }

    /**
     * D6: imagefilledpolygon() and imagepolygon() throw a ValueError for less than 3 points since PHP 8.0.
     *
     * @param int[] $points
     */
    #[DataProvider('polygonsWithTooFewPoints')]
    public function testDrawPolygonWithTooFewPointsDrawsNothing(array $points, bool $antialias): void
    {
        $before = Image::newCanvas(10, 10)->getDataPNG();

        $image = $this->assertNoPhpErrors(
            static fn() => Image::newCanvas(10, 10)->drawPolygon($points, '#FF0000', $antialias)
        );

        $this->assertSame($before, $image->getDataPNG());
    }

    /**
     * D2: writeTextAndGetBoundingBox() with ALIGN_* anchors called imagedestroy() (deprecated in PHP 8.5).
     */
    public function testWriteTextAndGetBoundingBoxWithAlignAnchorsReturnsBoundingBox(): void
    {
        $bbox = $this->assertNoPhpErrors(
            static fn() => Image::newCanvas(200, 100)->writeTextAndGetBoundingBox(
                'Test', self::FONT, 20, '#000000', 10, 10, Image::ALIGN_LEFT, Image::ALIGN_TOP
            )
        );

        $this->assertSame(['top-left', 'top-right', 'bottom-left', 'bottom-right', 'baseline'], \array_keys($bbox));
        $this->assertGreaterThan($bbox['top-left']['x'], $bbox['top-right']['x']);
        $this->assertGreaterThan($bbox['top-left']['y'], $bbox['bottom-left']['y']);
    }

    /**
     * D2: writeText() delegates to writeTextAndGetBoundingBox().
     */
    public function testWriteTextWithDefaultAnchors(): void
    {
        $image = $this->assertNoPhpErrors(
            static fn() => Image::newCanvas(200, 100)->writeText('Test', self::FONT, 20, '#000000', Image::ALIGN_CENTER, Image::ALIGN_MIDDLE)
        );

        $this->assertTrue($image->isImageDefined());
    }

    public function testWriteTextWithNumericAnchorsWritesAtBaseline(): void
    {
        $bbox = $this->assertNoPhpErrors(
            static fn() => Image::newCanvas(200, 100)->writeTextAndGetBoundingBox('Test', self::FONT, 20, '#000000', 10, 50, null, null)
        );

        $this->assertSame(['x' => 10, 'y' => 50], $bbox['baseline']);
    }
}
