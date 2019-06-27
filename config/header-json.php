<?php
$url = BASE_URL;
$userAgent = $_SERVER['HTTP_USER_AGENT'];
header("User-Agent: $userAgent");
header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + (60 * 60)));
header("cache-control: no-cache");
header("Access-Control-Allow-Origin: $url");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Connection: Close");