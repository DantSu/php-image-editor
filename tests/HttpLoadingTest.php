<?php

namespace DantSu\PHPImageEditor\Tests;

use DantSu\PHPImageEditor\Image;

/**
 * Loading images over HTTP with cURL, against PHP's built-in web server (see fixtures/http-router.php).
 */
final class HttpLoadingTest extends TestCase
{
    /** @var resource|null */
    private static $server = null;
    private static string $baseUrl = '';

    public static function setUpBeforeClass(): void
    {
        for ($attempt = 0; $attempt < 5 && self::$server === null; ++$attempt) {
            $port = \random_int(20000, 45000);
            $process = \proc_open(
                [PHP_BINARY, '-S', '127.0.0.1:' . $port, self::FIXTURES_DIR . '/http-router.php'],
                [0 => ['pipe', 'r'], 1 => ['file', '/dev/null', 'w'], 2 => ['file', '/dev/null', 'w']],
                $pipes
            );
            if (!\is_resource($process)) {
                continue;
            }

            // Wait up to 2 seconds until the server accepts connections.
            for ($i = 0; $i < 40; ++$i) {
                $socket = @\fsockopen('127.0.0.1', $port, $errorCode, $errorMessage, 0.05);
                if ($socket !== false) {
                    \fclose($socket);
                    self::$server = $process;
                    self::$baseUrl = 'http://127.0.0.1:' . $port;
                    break;
                }
                \usleep(50000);
            }

            if (self::$server === null) {
                \proc_terminate($process);
                \proc_close($process);
            }
        }
    }

    public static function tearDownAfterClass(): void
    {
        if (self::$server !== null) {
            \proc_terminate(self::$server);
            \proc_close(self::$server);
            self::$server = null;
        }
    }

    protected function setUp(): void
    {
        if (self::$server === null) {
            $this->markTestSkipped('Could not start PHP built-in web server.');
        }
    }

    public function testFromCurlLoadsImageOverHttp(): void
    {
        $image = $this->assertNoPhpErrors(static fn() => Image::fromCurl(self::$baseUrl . '/image.png'));

        $this->assertTrue($image->isImageDefined());
    }

    /**
     * D8: an HTML error page was passed to imagecreatefromstring() and raised a warning.
     */
    public function testFromCurlWithHttpErrorStatusReturnsUndefinedImageWithoutWarning(): void
    {
        $image = $this->assertNoPhpErrors(static fn() => Image::fromCurl(self::$baseUrl . '/not-found'));

        $this->assertFalse($image->isImageDefined());
    }

    public function testFromCurlWithHttpErrorStatusAndFailOnErrorDoesNotThrow(): void
    {
        // $failOnError only covers transport errors (cURL errors), not HTTP status codes.
        $image = $this->assertNoPhpErrors(static fn() => Image::fromCurl(self::$baseUrl . '/not-found', [], true));

        $this->assertFalse($image->isImageDefined());
    }

    /**
     * Options passed by the caller take precedence over the default options.
     */
    public function testFromCurlCallerOptionsOverrideDefaults(): void
    {
        $default = $this->assertNoPhpErrors(static fn() => Image::fromCurl(self::$baseUrl . '/user-agent'));
        $custom = $this->assertNoPhpErrors(
            static fn() => Image::fromCurl(self::$baseUrl . '/user-agent', [CURLOPT_USERAGENT => 'php-image-editor-tests'])
        );

        $this->assertFalse($default->isImageDefined(), 'Default user agent should be rejected by the test server.');
        $this->assertTrue($custom->isImageDefined(), 'Custom user agent should override the default one.');
    }
}
