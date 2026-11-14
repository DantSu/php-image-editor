<?php

/**
 * Router script for PHP's built-in web server, used by HttpLoadingTest.
 *
 * Routes:
 * - /image.png   200 with a PNG image
 * - /not-found   404 with an HTML error page
 * - /user-agent  200 with a PNG image if the user agent is "php-image-editor-tests", otherwise 403
 */

$image = __DIR__ . '/../../src/samples/resources/watermark.png';

switch (\parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) {
    case '/image.png':
        \header('Content-Type: image/png');
        \readfile($image);
        break;

    case '/user-agent':
        if (($_SERVER['HTTP_USER_AGENT'] ?? '') !== 'php-image-editor-tests') {
            \http_response_code(403);
            echo '<html><body>Forbidden</body></html>';
            break;
        }
        \header('Content-Type: image/png');
        \readfile($image);
        break;

    default:
        \http_response_code(404);
        echo '<html><body>Not found</body></html>';
}
