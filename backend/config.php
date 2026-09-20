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
   BASE URL — ab folder ke naam pe depend nahi karta.
   __DIR__ (ye file kahan hai) minus DOCUMENT_ROOT (htdocs
   kahan se start hota hai) = beech mein jo bache wahi
   project ka subfolder-prefix hai (chahe naam "shivam" ho,
   "rahul" ho, ya "sms_teacher").
   ===================================================== */

if ($isLocal) {

    // Windows ke backslashes ko forward-slash me normalize karo,
    // taaki comparison sahi se ho (URLs hamesha forward-slash use karte hain)
    $projectDir = str_replace('\\', '/', __DIR__);           // .../htdocs/<folder>/backend
    $docRoot    = str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/\\'));

    // docRoot ko project path se hata do — jo bacha wahi prefix hai
    $relative = $docRoot !== '' ? str_replace($docRoot, '', $projectDir) : $projectDir;

    // is file ka apna folder "backend" hai, project root nahi —
    // isliye last "/backend" segment ko hata do
    $relative = preg_replace('#/backend$#', '', $relative);

    define('BASE_URL', $relative);

} else {

    // Server pe project seedha htdocs ke andar hota hai, koi prefix nahi
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

    // Starting slash ensure karo
    $path = '/' . ltrim($path, '/');

    return (BASE_URL ?: '') . $path;
}

?>