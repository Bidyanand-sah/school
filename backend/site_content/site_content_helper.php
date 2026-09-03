<?php
function getContent($key) {
    $live = __DIR__ . '/data/site_content.json';
    $def  = __DIR__ . '/data/site_content_defaults.json';

    $current  = file_exists($live) ? json_decode(file_get_contents($live), true) : [];
    $defaults = file_exists($def)  ? json_decode(file_get_contents($def), true)  : [];

    if (!empty($current[$key])) return $current[$key];
    return $defaults[$key] ?? '';
}
?>