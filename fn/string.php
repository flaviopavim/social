<?php

function pre($pre) {
    echo '<pre>';
    print_r($pre);
    echo '</pre>';
    exit;
}

function c($decimal) {
    return 'R$ ' . number_format($decimal, 2, ',', '');
}

function d($coin) {
    $coin = str_replace('R$ ', '', $coin);
    $coin = str_replace('.', '', $coin);
    $coin = str_replace(',', '.', $coin);
    return $coin;
}

function extension($file) {
    $x = explode('.', $file);
    $end = end($x);
    return strtolower($end);
}

function randString($chars = 32) {
    $matrix = 'abcdefghijklmnopqrstuvwxyz';
    $string = '';
    for ($i = 0; $i < $chars; $i++) {
        $string .= $matrix[rand(0, strlen($matrix) - 1)];
    }
    return $string;
}

function name($array) {
    if (!empty($array['name'])) {
        return $array['name'];
    } else if (!empty($array['nick'])) {
        return $array['nick'];
    } else if (!empty($array['email'])) {
        return $array['email'];
    } else if (!empty($array['id'])) {
        return $array['id'];
    }
    return '';
}

function nick($array) {
    if (!empty($array['nick'])) {
        return $array['nick'];
    } else if (!empty($array['name'])) {
        return $array['name'];
    } else if (!empty($array['email'])) {
        return $array['email'];
    } else if (!empty($array['id'])) {
        return $array['id'];
    }
    return '';
}

function cutText($string, $letters = 128) {
    $words = explode(' ', $string);
    $return = '';
    foreach ($words as $word) {
        if (strlen($return . $word . ' ') <= $letters) {
            $return .= $word . ' ';
        }
    }
    $return = substr($return, 0, -1);
    if ($return != $string) {
        $return .= '...';
    }
    return $return;
}

function cleanUrl($url) {
    $new = false;
    //verifica as pastas reservadas
    foreach (array('adm', 'auth', 'error', 'inc', 'shop', 'site') as $path) {
        if (file_exists('frontend/view/' . $path . '/' . $url . '.php')) {
            $new = true;
        }
    }
    
    foreach(array('content','page','user') as $table) {
        alter($table,'clean_url');
        $f=f("SELECT clear_url FROM `".$table."` WHERE clear_url='".$url."'");
        $new=empty($f['id'])?$new:true;
    }
    
    if ($new) {
        if (strpos($new, '-') > 0) {
            $x = explode('-', $new);
            $last = end($x);
            if (is_numeric($last)) {
                $url=substr($url,0,-strlen($last));
                $url.= $last;
            }
        } else {
            $url.= '-2';
        }
        return cleanUrl($url);
    }
    return $url;
}

function startsWith( $haystack, $needle ) {
     $length = strlen( $needle );
     return substr( $haystack, 0, $length ) === $needle;
}

function endsWith( $haystack, $needle ) {
    $length = strlen( $needle );
    if( !$length ) {
        return true;
    }
    return substr( $haystack, -$length ) === $needle?true:false;
}

function removeIfLastCharIs($string,$lastchar) {
//    return endsWith($string,$lastchar)===true?substr($string,0,-1):$string;
    return $string;
}