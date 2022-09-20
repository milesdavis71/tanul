<?php
if (!function_exists('safe_require_once')) {
    function safe_require_once($path = '') {
        $path = get_template_directory() . '/' . $path;
        file_exists($path) ? require_once($path) : null;
    }
}

// setup theme
safe_require_once('setup-theme.php');
