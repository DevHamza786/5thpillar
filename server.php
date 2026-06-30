<?php

$publicPath = getcwd();

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

$query = (string) ($_SERVER['QUERY_STRING'] ?? '');

// Open site PDFs in branded viewer (browser tab favicon). ?embed=1 serves raw file for iframe.
if (
    is_string($uri)
    && preg_match('#^/assets/pdf/.+\.pdf$#i', $uri)
    && ! preg_match('/(?:^|[&?])embed=1(?:&|$)/', $query)
) {
    header('Location: /pdf-viewer'.$uri, true, 302);
    exit;
}

// This file allows us to emulate Apache's "mod_rewrite" functionality from the
// built-in PHP web server. This provides a convenient way to test a Laravel
// application without having installed a "real" web server software here.
if ($uri !== '/' && file_exists($publicPath.$uri)) {
    return false;
}

$formattedDateTime = date('D M j H:i:s Y');

$requestMethod = $_SERVER['REQUEST_METHOD'];
$remoteAddress = $_SERVER['REMOTE_ADDR'].':'.$_SERVER['REMOTE_PORT'];

file_put_contents('php://stdout', "[$formattedDateTime] $remoteAddress [$requestMethod] URI: $uri\n");

require_once $publicPath.'/index.php';
