<?php


//$view = 'products';
$view = 'home';

if (!empty($_GET[0])) {
    $view = $_GET[0];
}

//verifica de qual pasta é
foreach(array('adm','auth','error','inc','shop','site') as $path_) {
    if (file_exists('frontend/view/' . $path_ . '/' . $view . '.php')) {
        if ($path_!='inc') { //ignora a pasta inc
            $path=$path_;
            break;
        }
    }
}

//se não encontrar a pasta
if (empty($path)) {
    $path = 'error';
    $view = 'default';
}