<?php
function sh($s, $l = 3) {
    for ($i = 0; $i < strlen($s); $i++) {
        if (!isset($g) or $g == $l) {
            $g = 0;
        }
        $a[$g][] = $s[$i];
        $g++;
    }
    $s = '';
    foreach ($a as $f) {
        foreach ($f as $v) {
            $s .= $v;
        }
    }
    return $s;
}
function unsh($s, $l = 3) {
    $c = $a = 0;
    for ($i = 0; $i < strlen($s); $i++) {
        $arr[$c] = $s[$i];
        if ($c < (strlen($s) - $l)) {
            $c += $l;
        } else {
            $a++;
            $c = $a;
        }
    }
    $r = '';
    for ($i = 0; $i < strlen($s); $i++) {
        $r .= $arr[$i];
    }
    return $r;
}
//outputs
//$txt="Hello World";
//$level=3;
//echo 'Message: '.$txt;
//echo ' - ';
//echo 'Level: '.$level;
//echo '<br>';
//$sh=sh($txt,$level);
//echo 'Shuffle: ';
//echo $sh;
//echo '<br>';
//$unsh=unsh($sh,$level);
//echo 'Unshuffle: ';
//echo $unsh;