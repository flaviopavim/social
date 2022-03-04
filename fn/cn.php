<?php

$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
session_start();
date_default_timezone_set('America/Sao_Paulo');
$base = 'whitehats';
if (strpos($actual_link, 'localhost/') > 0) {
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $systemUrl = 'http://localhost/base/';
    $base = 'base';
} else {
    $host = 'localhost';
    $user = 'whitehats';
    $pass = '';
    $systemUrl = 'https://whitehats.com.br/';
    $web = true;
}