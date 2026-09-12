<?php

/**
 * Application Configuration
 *
 * This file handles application URL/path configuration only.
 * Database connection is handled separately by backend/con1.php.
 */


/* =====================================================
   BASE URL
   ===================================================== */

/*
 * Local XAMPP:
 * http://localhost/sms_teacher/
 *
 * InfinityFree:
 * https://yourdomain.com/
 *
 * The URL is detected automatically.
 */

if (
    isset($_SERVER['HTTP_HOST']) &&
    (
        strpos($_SERVER['HTTP_HOST'], 'localhost') !== false ||
        strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false
    )
) {
    // Local XAMPP
    define('BASE_URL', '/sms_teacher');
} else {
    // Live Server / InfinityFree
    define('BASE_URL', '');
}


/* =====================================================
   APP URL HELPER
   ===================================================== */

/**
 * Create a URL for any file/folder inside this project.
 *
 * Local example:
 * app_url('/home_page/about/about.php')
 *
 * Result:
 * /sms_teacher/home_page/about/about.php
 *
 * Live example:
 * app_url('/home_page/about/about.php')
 *
 * Result:
 * /home_page/about/about.php
 */

function app_url($path = '')
{
    $path = trim((string)$path);

    // If no path is supplied
    if ($path === '') {
        return BASE_URL ?: '/';
    }

    /*
     * Do not modify external URLs.
     *
     * Example:
     * https://google.com
     * http://example.com
     */
    if (preg_match('#^(?:https?:)?//#i', $path)) {
        return $path;
    }

    /*
     * Remove old hard-coded /sms_teacher
     * if it accidentally exists in a path.
     *
     * Example:
     * /sms_teacher/uploads/photo.jpg
     *
     * becomes:
     * /uploads/photo.jpg
     */
    $path = preg_replace(
        '#^/sms_teacher(?:/|$)#i',
        '/',
        $path
    );

    /*
     * Make sure the path starts with /
     */
    $path = '/' . ltrim($path, '/');

    /*
     * Add the current environment's BASE_URL.
     */
    return (BASE_URL ?: '') . $path;
}

?>