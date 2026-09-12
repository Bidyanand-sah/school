<?php

/* =====================================================
   ENVIRONMENT DETECTION
   ===================================================== */

$serverHost = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? '';

$serverHost = strtolower($serverHost);
$serverHost = preg_replace('/:\d+$/', '', $serverHost);

$isLocal = in_array(
    $serverHost,
    ['localhost', '127.0.0.1', '::1'],
    true
);


/* =====================================================
   BASE URL
   ===================================================== */

if ($isLocal) {

    // XAMPP:
    // http://localhost/sms_teacher/

    define('BASE_URL', '/sms_teacher');

} else {

    // InfinityFree:
    // https://yourdomain.com/

    define('BASE_URL', '');
}


/* =====================================================
   APP URL HELPER
   ===================================================== */

function app_url($path = '')
{
    $path = trim((string)$path);

    if ($path === '') {
        return BASE_URL ?: '/';
    }

    // External URL ko change nahi karna
    if (preg_match('#^(?:https?:)?//#i', $path)) {
        return $path;
    }

    // Purana /sms_teacher prefix hata do
    $path = preg_replace(
        '#^/sms_teacher(?:/|$)#i',
        '/',
        $path
    );

    // Starting slash ensure karo
    $path = '/' . ltrim($path, '/');

    return (BASE_URL ?: '') . $path;
}

?>