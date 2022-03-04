<?php

function pgn($total, $pg) {
    return 'Paginação ' . $total . ' ' . $pg;
}

function im($img) {
    global $root;
    if (file_exists('file/' . $img)) {
        return '<img src="' . $root . 'backend/php/im.php?url=../../file/' . $img . '" class="img-responsive">';
    }
}

function img($img) {
    global $root;
//    if (file_exists('frontend/img/' . $img)) {
        return '<img src="' . $root . 'backend/php/im.php?url=../../frontend/img/' . $img . '" class="img-responsive">';
//    }
}
