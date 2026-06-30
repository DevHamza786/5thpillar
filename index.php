<?php

/**
 * Front controller when the hosting document root is the project root (not public/).
 * Requests to / are served by this file via DirectoryIndex — avoids / → public/ redirect loops.
 */
require __DIR__.'/public/index.php';
