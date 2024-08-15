<?php
define('WEB_NAME','PADMATIRTA GROUP CAREER');
function site_url($path = '') {
    $base_url = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $base_url .= '://' . $_SERVER['HTTP_HOST'];
    $base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
    return $base_url . ltrim($path, '/');
}