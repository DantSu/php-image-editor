<?php

namespace DantSu\PHPImageEditor\Tests;

use DantSu\PHPImageEditor\Image;

/**
 * Creating, cloning, transforming and destroying images.
 */
final class LifecycleTest extends TestCase
{
    public function testNewCanvasIsTransparent(): void
    {
        $image = $this->assertNoPhpErrors(static fn() => Image::newCanvas(4, 3));

        $this->assertTrue($image->isImageDefined());
        $this->assertSame([4, 3], [$image->getWidth(), $image->getHeight()]);
        $this->assertSame(127, (\imagecolorat($image->getImage(), 0, 0) >> 24) & 0x7F);
    }

    /**
     * D4: destroy() called imagedestroy() (deprecated in PHP 8.5).
     */
    public function testDestroyLeavesUndefinedImage(): void
    {
        $image = $this->assertNoPhpErrors(static fn() => Image::newCanvas(4, 4)->destroy());

        $this->assertFalse($image->isImageDefined());
    }

    public function testDestroyOnUndefinedImageIsNoOp(): void
    {
        $image = $this->assertNoPhpErrors(static fn() => Image::fromData('')->destroy());

        $this->assertFalse($image->isImageDefined());
    }

    /**
     * D3: alphaMask() called imagedestroy() (deprecated in PHP 8.5).
     */
    public function testAlphaMaskAppliesTransparency(): void
    {
        $image = $this->assertNoPhpErrors(static function (): Image {
            $mask = Image::newCanvas(2, 1);
            // Left pixel white (opaque), right pixel black (transparent).
            \imagesetpixel($mask->getImage(), 0, 0, \imagecolorallocatealpha($mask->getImage(), 255, 255, 255, 0));
            \imagesetpixel($mask->getImage(), 1, 0, \imagecolorallocatealpha($mask->getImage(), 0, 0, 0, 0));

            return Image::newCanvas(2, 1)
                ->drawRectangle(0, 0, 2, 1, '#FF0000')
                ->alphaMask($mask);
        });

        $this->assertSame(0, (\imagecolorat($image->getImage(), 0, 0) >> 24) & 0x7F);
        $this->assertSame(127, (\imagecolorat($image->getImage(), 1, 0) >> 24) & 0x7F);
    }

    public function testCloneIsIndependentCopy(): void
    {
        $original = Image::newCanvas(4, 4);
        $copy = $this->assertNoPhpErrors(static fn() => clone $original);
        $copy->drawRectangle(0, 0, 4, 4, '#FF0000');

        $this->assertNotSame($original->getImage(), $copy->getImage());
        $this->assertSame(127, (\imagecolorat($original->getImage(), 0, 0) >> 24) & 0x7F);
    }
}
