<?php

namespace DantSu\PHPImageEditor\Tests;

use DantSu\PHPImageEditor\Image;

/**
 * Base class for all tests.
 *
 * Provides helpers to
 * - collect PHP deprecations, warnings and notices raised by a piece of code and assert there are none,
 * - compare an image against a stored reference image ("golden image") with a small tolerance.
 *
 * Golden images live in tests/fixtures/golden. To (re)generate them, run the suite with UPDATE_GOLDEN=1.
 */
abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    protected const RESOURCES_DIR = __DIR__ . '/../src/samples/resources';
    protected const FIXTURES_DIR = __DIR__ . '/fixtures';
    protected const GOLDEN_DIR = __DIR__ . '/fixtures/golden';

    /**
     * Maximum difference per channel (0-255, alpha scaled to the same range) for two pixels to count as equal.
     * Absorbs small rendering differences between GD, libjpeg and FreeType builds.
     */
    protected const PIXEL_CHANNEL_TOLERANCE = 8;

    /**
     * Maximum share of pixels that may differ beyond PIXEL_CHANNEL_TOLERANCE.
     */
    protected const MAX_DIFFERENT_PIXEL_RATIO = 0.01;

    private const INSUPPRESSIBLE_LEVELS = E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR | E_RECOVERABLE_ERROR;

    /**
     * Run $callback and collect every PHP error (deprecation, warning, notice, ...) it raises.
     *
     * @param callable $callback Code under test
     * @param mixed $result Receives the return value of $callback
     * @return string[] Human-readable error messages
     */
    protected function collectPhpErrors(callable $callback, &$result = null): array
    {
        $errors = [];
        \set_error_handler(static function (int $severity, string $message, string $file, int $line) use (&$errors): bool {
            // Respect the @ operator. Do not compare against error_reporting() directly: PHPUnit lowers it while a
            // test runs and handles all other levels itself. Inside @, only the insuppressible levels remain set.
            if ((\error_reporting() & ~self::INSUPPRESSIBLE_LEVELS) === 0) {
                return false;
            }
            $errors[] = \sprintf('[%s] %s in %s:%d', static::severityName($severity), $message, \basename($file), $line);
            return true;
        });

        try {
            $result = $callback();
        } finally {
            \restore_error_handler();
        }

        return $errors;
    }

    /**
     * Run $callback, assert that it raises no PHP error at all and return its result.
     *
     * @param callable $callback Code under test
     * @return mixed Return value of $callback
     */
    protected function assertNoPhpErrors(callable $callback)
    {
        $errors = $this->collectPhpErrors($callback, $result);
        $this->assertSame([], $errors, 'Expected no PHP deprecations, warnings or notices.');
        return $result;
    }

    /**
     * Assert that $image looks like the stored golden image $name.
     *
     * @param string $name File name (without extension) inside tests/fixtures/golden
     * @param Image $image Image under test
     */
    protected function assertImageMatchesGolden(string $name, Image $image): void
    {
        $this->assertTrue($image->isImageDefined(), 'Image under test is not defined.');

        $goldenPath = static::GOLDEN_DIR . '/' . $name . '.png';

        if (\getenv('UPDATE_GOLDEN') === '1') {
            if (!\is_dir(static::GOLDEN_DIR)) {
                \mkdir(static::GOLDEN_DIR, 0777, true);
            }
            \file_put_contents($goldenPath, $image->getDataPNG());
        }

        $this->assertFileExists($goldenPath, 'Golden image missing. Run the tests with UPDATE_GOLDEN=1 to create it.');

        $expected = \imagecreatefrompng($goldenPath);
        $actual = $image->getImage();

        $this->assertSame(
            [\imagesx($expected), \imagesy($expected)],
            [\imagesx($actual), \imagesy($actual)],
            'Image size differs from golden image "' . $name . '".'
        );

        $ratio = static::differentPixelRatio($expected, $actual);
        $this->assertLessThanOrEqual(
            static::MAX_DIFFERENT_PIXEL_RATIO,
            $ratio,
            \sprintf('%.2f %% of the pixels differ from golden image "%s".', $ratio * 100, $name)
        );
    }

    /**
     * Share of pixels whose color or alpha differs by more than PIXEL_CHANNEL_TOLERANCE.
     *
     * @param \GdImage $expected
     * @param \GdImage $actual
     * @return float Value between 0 and 1
     */
    private static function differentPixelRatio(\GdImage $expected, \GdImage $actual): float
    {
        $width = \imagesx($expected);
        $height = \imagesy($expected);
        $different = 0;

        for ($y = 0; $y < $height; ++$y) {
            for ($x = 0; $x < $width; ++$x) {
                $a = \imagecolorat($expected, $x, $y);
                $b = \imagecolorat($actual, $x, $y);
                if ($a === $b) {
                    continue;
                }
                $diff = \max(
                    \abs((($a >> 16) & 0xFF) - (($b >> 16) & 0xFF)),
                    \abs((($a >> 8) & 0xFF) - (($b >> 8) & 0xFF)),
                    \abs(($a & 0xFF) - ($b & 0xFF)),
                    \abs((($a >> 24) & 0x7F) - (($b >> 24) & 0x7F)) * 2
                );
                if ($diff > static::PIXEL_CHANNEL_TOLERANCE) {
                    ++$different;
                }
            }
        }

        return $different / ($width * $height);
    }

    private static function severityName(int $severity): string
    {
        switch ($severity) {
            case E_DEPRECATED:
            case E_USER_DEPRECATED:
                return 'DEPRECATED';
            case E_WARNING:
            case E_USER_WARNING:
                return 'WARNING';
            case E_NOTICE:
            case E_USER_NOTICE:
                return 'NOTICE';
            default:
                return 'E' . $severity;
        }
    }
}
