<?php

namespace DantSu\PHPImageEditor\Tests;

use DantSu\PHPImageEditor\Image;

/**
 * Loading images from files, raw data and URLs.
 *
 * Convention of this library: if an image cannot be loaded, the returned instance is "undefined"
 * (isImageDefined() === false) and all further operations are no-ops. Loading must never raise PHP warnings.
 */
final class LoadingTest extends TestCase
{
    private const SERVER_KEYS = ['REQUEST_SCHEME', 'HTTP_HOST', 'REQUEST_URI'];

    public function testFromPathLoadsPng(): void
    {
        $image = $this->assertNoPhpErrors(static fn() => Image::fromPath(self::RESOURCES_DIR . '/watermark.png'));

        $this->assertTrue($image->isImageDefined());
        $this->assertSame(3, $image->getType());
        $this->assertGreaterThan(0, $image->getWidth());
        $this->assertGreaterThan(0, $image->getHeight());
    }

    public function testFromPathLoadsJpeg(): void
    {
        $image = $this->assertNoPhpErrors(static fn() => Image::fromPath(self::RESOURCES_DIR . '/photo.jpg'));

        $this->assertTrue($image->isImageDefined());
        $this->assertSame(2, $image->getType());
    }

    /**
     * D7: getimagesize() raised "Failed to open stream" before returning false.
     */
    public function testFromPathWithMissingFileReturnsUndefinedImageWithoutWarning(): void
    {
        $image = $this->assertNoPhpErrors(static fn() => Image::fromPath(self::FIXTURES_DIR . '/does-not-exist.png'));

        $this->assertFalse($image->isImageDefined());
    }

    /**
     * D7: getimagesize() raised a notice/warning for files that are not images.
     */
    public function testFromPathWithNonImageFileReturnsUndefinedImageWithoutWarning(): void
    {
        $image = $this->assertNoPhpErrors(static fn() => Image::fromPath(self::FIXTURES_DIR . '/not-an-image.txt'));

        $this->assertFalse($image->isImageDefined());
    }

    /**
     * D7: getimagesize() raised "Error reading from" for empty files.
     */
    public function testFromPathWithEmptyFileReturnsUndefinedImageWithoutWarning(): void
    {
        $image = $this->assertNoPhpErrors(static fn() => Image::fromPath(self::FIXTURES_DIR . '/empty.png'));

        $this->assertFalse($image->isImageDefined());
    }

    public function testFromPathWithUnsupportedFormatReturnsUndefinedImage(): void
    {
        $path = \sys_get_temp_dir() . '/php-image-editor-test-' . \bin2hex(\random_bytes(4)) . '.bmp';
        \imagebmp(\imagecreatetruecolor(2, 2), $path);

        try {
            $image = $this->assertNoPhpErrors(static fn() => Image::fromPath($path));
        } finally {
            \unlink($path);
        }

        $this->assertFalse($image->isImageDefined());
        $this->assertSame(0, $image->getWidth());
    }

    public function testPathFailureResetsPreviouslyLoadedImage(): void
    {
        $image = Image::fromPath(self::RESOURCES_DIR . '/watermark.png');

        $this->assertNoPhpErrors(static fn() => $image->path(self::FIXTURES_DIR . '/does-not-exist.png'));

        $this->assertFalse($image->isImageDefined());
        $this->assertSame(0, $image->getWidth());
    }

    public function testFromPathAcceptsFileUrl(): void
    {
        $url = $this->fileUrl(self::RESOURCES_DIR . '/watermark.png');

        $image = $this->assertNoPhpErrors(static fn() => Image::fromPath($url));

        $this->assertTrue($image->isImageDefined());
    }

    /**
     * D7: getWidth(), getHeight() and getType() are declared as int but returned null for an undefined image (TypeError).
     */
    public function testGettersOfUndefinedImageReturnZero(): void
    {
        $image = new Image();

        $this->assertSame(0, $image->getWidth());
        $this->assertSame(0, $image->getHeight());
        $this->assertSame(0, $image->getType());
    }

    public function testFromDataLoadsImage(): void
    {
        $data = \file_get_contents(self::RESOURCES_DIR . '/watermark.png');

        $image = $this->assertNoPhpErrors(static fn() => Image::fromData($data));

        $this->assertTrue($image->isImageDefined());
    }

    /**
     * D8: imagecreatefromstring('') throws a ValueError since PHP 8.0.
     */
    public function testFromDataWithEmptyStringReturnsUndefinedImage(): void
    {
        $image = $this->assertNoPhpErrors(static fn() => Image::fromData(''));

        $this->assertFalse($image->isImageDefined());
    }

    /**
     * D8: imagecreatefromstring() raised "Data is not in a recognized format".
     */
    public function testFromDataWithInvalidDataReturnsUndefinedImageWithoutWarning(): void
    {
        $image = $this->assertNoPhpErrors(static fn() => Image::fromData('This is not an image.'));

        $this->assertFalse($image->isImageDefined());
    }

    /**
     * D1 (curl_close() deprecated in PHP 8.5) and D5 (undefined $_SERVER keys on the command line).
     */
    public function testFromCurlLoadsImageOnCommandLineWithoutWarning(): void
    {
        $url = $this->fileUrl(self::RESOURCES_DIR . '/watermark.png');

        $image = $this->withoutServerKeys(fn() => $this->assertNoPhpErrors(static fn() => Image::fromCurl($url)));

        $this->assertTrue($image->isImageDefined());
        $this->assertSame(
            [\imagesx(\imagecreatefrompng(self::RESOURCES_DIR . '/watermark.png')), \imagesy(\imagecreatefrompng(self::RESOURCES_DIR . '/watermark.png'))],
            [$image->getWidth(), $image->getHeight()]
        );
    }

    /**
     * D1: in a web request context the referer is built from $_SERVER; this must work without errors as well.
     */
    public function testFromCurlLoadsImageInWebContextWithoutWarning(): void
    {
        $url = $this->fileUrl(self::RESOURCES_DIR . '/watermark.png');

        $image = $this->withServerKeys(
            ['REQUEST_SCHEME' => 'https', 'HTTP_HOST' => 'example.org', 'REQUEST_URI' => '/map.php'],
            fn() => $this->assertNoPhpErrors(static fn() => Image::fromCurl($url))
        );

        $this->assertTrue($image->isImageDefined());
    }

    public function testFromCurlWithUnreachableUrlReturnsUndefinedImage(): void
    {
        $url = $this->fileUrl(self::FIXTURES_DIR . '/does-not-exist.png');

        $image = $this->withoutServerKeys(fn() => $this->assertNoPhpErrors(static fn() => Image::fromCurl($url)));

        $this->assertFalse($image->isImageDefined());
    }

    public function testFromCurlWithFailOnErrorThrowsException(): void
    {
        $url = $this->fileUrl(self::FIXTURES_DIR . '/does-not-exist.png');

        $this->expectException(\Exception::class);

        // PHP errors are covered by the other tests; only the exception matters here.
        $this->withoutServerKeys(fn() => $this->collectPhpErrors(static fn() => Image::fromCurl($url, [], true)));
    }

    /**
     * D8: a response that is not an image (e.g. an HTML error page) raised "Data is not in a recognized format".
     */
    public function testFromCurlWithNonImageResponseReturnsUndefinedImageWithoutWarning(): void
    {
        $url = $this->fileUrl(self::FIXTURES_DIR . '/not-an-image.txt');

        $image = $this->withoutServerKeys(fn() => $this->assertNoPhpErrors(static fn() => Image::fromCurl($url)));

        $this->assertFalse($image->isImageDefined());
    }

    /**
     * D8: an empty response made imagecreatefromstring() throw a ValueError.
     */
    public function testFromCurlWithEmptyResponseReturnsUndefinedImage(): void
    {
        $url = $this->fileUrl(self::FIXTURES_DIR . '/empty.png');

        $image = $this->withoutServerKeys(fn() => $this->assertNoPhpErrors(static fn() => Image::fromCurl($url)));

        $this->assertFalse($image->isImageDefined());
    }

    private function fileUrl(string $path): string
    {
        $realPath = \realpath(\dirname($path));
        $this->assertNotFalse($realPath, 'Fixture directory not found: ' . \dirname($path));
        return 'file://' . $realPath . '/' . \basename($path);
    }

    /**
     * Run $callback as on the command line, where the request related $_SERVER keys do not exist.
     */
    private function withoutServerKeys(callable $callback)
    {
        return $this->withServerKeys([], $callback);
    }

    /**
     * Run $callback with exactly the given request related $_SERVER keys and restore $_SERVER afterwards.
     *
     * @param array<string, string> $values
     */
    private function withServerKeys(array $values, callable $callback)
    {
        $backup = $_SERVER;
        foreach (self::SERVER_KEYS as $key) {
            unset($_SERVER[$key]);
        }
        $_SERVER = \array_merge($_SERVER, $values);

        try {
            return $callback();
        } finally {
            $_SERVER = $backup;
        }
    }
}
