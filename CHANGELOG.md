# Changelog

All notable changes of this fork are documented in this file.
For releases up to 1.4.5, see the [original repository](https://github.com/DantSu/php-image-editor).

## [Unreleased] - 1.5.0

First release of the fork `gexo/php-image-editor`.

### Changed

- Package renamed to `gexo/php-image-editor`. It declares `replace: dantsu/php-image-editor` and keeps the
  `DantSu\PHPImageEditor` namespace, so it is a drop-in replacement for the original package.
- **Requires PHP 8.1 or newer** and the `curl`, `gd` and `mbstring` extensions.
- `curl()` / `fromCurl()`: options passed in `$curlOptions` now take precedence over the default options
  (user agent, referer, timeout). Previously the defaults could not be overridden.
- `curl()` / `fromCurl()`: a response with an HTTP status outside 2xx results in an undefined image instead of
  trying to decode the error page. `$failOnError` still only covers transport errors.
- `path()` / `fromPath()`: if loading fails, the image is reset to undefined. Previously a failing `path()` call
  on an already loaded instance kept the old image.

### Fixed

- No more PHP 8.5 deprecations: removed calls to `curl_close()` and `imagedestroy()`, which have no effect since PHP 8.0.
- `curl()` / `fromCurl()`: no "Undefined array key" warnings on the command line. The referer is only sent
  in a web request context.
- `drawPolygon()`: no `ValueError` for less than 3 points; nothing is drawn instead.
- `path()` / `fromPath()`: no warnings or notices for missing, unreadable, empty or non-image files, and no
  `TypeError` for unsupported image formats (e.g. WebP, BMP). The image is undefined instead.
- `data()` / `fromData()`: no warnings or notices for empty or unrecognized data. The image is undefined instead.
- `getWidth()`, `getHeight()`, `getType()`: return `0` for an undefined image instead of throwing a `TypeError`.

### Added

- PHPUnit test suite including golden image tests for all samples, and a GitHub Actions workflow for PHP 8.1 - 8.5.
