function cesar($s, $n, $rev) {
    var $matriz = new Array();
    $matriz[0] = 'abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz';
    $matriz[1] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZABCDEFGHIJKLMNOPQRSTUVWXYZ';
    for (var $a = 0; $a <= 1; $a++) {
        var $m = $matriz[$a];
        var $r = '';
        for (var $i = 0; $i < strlen($s); $i++) {
            var $p = $m.indexOf($s[$i]);
            var $soma;
            if ($p >= 0) {
                if ($rev) {
                    $soma = ($p) - $n;
                } else {
                    $soma = ($p) + $n;
                }
                while ($soma >= 52) {
                    $soma -= 26;
                }
                while ($soma < 0) {
                    $soma += 26;
                }
                $r += $m[$soma];
            } else {
                $r += $s[$i];
            }
        }
        $s = $r;
    }
    return $r;
}

function charVal($c) {
    var $m = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    var $p = $m.indexOf($c);
    if ($p > 0) {
        return $p;
    }
    return 0;
}

function viginere($string, $key, $reverse) {
    //modificado em 28/12/2020
    var $kl = strlen($key);
    var $count = 0;
    var $r = '';
    for (var $i = 0; $i < strlen($string); $i++) {
        if ($count == $kl) {
            $count = 0;
        }
        var cv = charVal($key[$count]);
        if (cv > 0) {
            $r += cesar($string[$i], $reverse?-cv:cv);
            $count++;
        } else {
            $r+=$string[$i];
        }
    }
    return $r;
}
function sh(s, d) {
    var c = 1; //contador
    var c2 = 0; //contador2
    var ns = [s.length];
    for (var i = 0; i < s.length; i++) {
        if (c > d) {
            c = 1;
        }
        ns[c2] = {c: c, v: s[i]};
        c++;
        c2++;
    }
    var r = '';
    for (var a = 1; a <= d; a++) {
        for (var i = 0; i < s.length; i++) {
            if (ns[i].c === a) {
                if (ns[i].c) {
                    r += ns[i].v;
                }
            }
        }
    }
    return r;
}

function unsh(s, l) {
    var c = 0;
    var a = 0;
    var arr = new Array();
    for (var i = 0; i < s.length; i++) {
        arr[c] = s[i];
        if (c < (s.length - l)) {
            c += l;
        } else {
            a++;
            c = a;
        }
    }
    var r = '';
    for (var i = 0; i < s.length; i++) {
        r += arr[i];
    }
    return r;
}

function randString(length) {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}
//function cr($s, $key) {
//    $s = base64_encode($s);
//    var $x = $key.split(':');
//    var $v = $x[0];
//    var $n = $x[1];
//    var $a1 = $x[2].split(',');
//    var $a2 = $x[3].split(',');
//    var $a3 = $x[4].split(',');
//    var $a4 = $x[5].split(',');
//    var $a5 = $x[6].split(',');
//    var $a6 = $x[7].split(',');
//    for (var $i = $n; $i >= 1; $i--) {
//        if (in_array($i, $a1)) {
//            $s = sh($s, $i);
//        }
//        if (in_array($i, $a2)) {
//            $s = strrev($s);
//        }
//        if (in_array($i, $a3)) {
//            $s = unsh($s, $i);
//        }
//        if (in_array($i, $a4)) {
//            $s = base64_encode($s);
//        }
//        if (in_array($i, $a5)) {
//            $s = viginere($s, $v, false);
//        }
//        if (in_array($i, $a6)) {
//            $s = $s + "" + randString(1);
//        }
//    }
////    $s = str_replace('=', '@$2', $s);
////    $s = str_replace('&', '@$1', $s);
////    $s = encodeURIComponent($s);
//    return $s;
//}
//function dc($s, $key) {
////    $s = decodeURIComponent($s);
////    $s = str_replace('@$1', '&', $s);
////    $s = str_replace('@$2', '=', $s);
//    var $x = $key.split(':');
//    var $v = $x[0];
//    var $n = $x[1];
//    var $a1 = $x[2].split(',');
//    var $a2 = $x[3].split(',');
//    var $a3 = $x[4].split(',');
//    var $a4 = $x[5].split(',');
//    var $a5 = $x[6].split(',');
//    var $a6 = $x[7].split(',');
//    for (var $i = 1; $i <= $n; $i++) {
//        if (in_array($i, $a6)) {
//            $s = substr($s, 0, -1);
//        }
//        if (in_array($i, $a5)) {
//            $s = viginere($s, $v, true);
//        }
//        if (in_array($i, $a4)) {
//            $s = base64_decode($s);
//        }
//        if (in_array($i, $a3)) {
//            $s = sh($s, $i);
//        }
//        if (in_array($i, $a2)) {
//            $s = strrev($s);
//        }
//        if (in_array($i, $a1)) {
//            $s = unsh($s, $i);
//        }
//    }
//    $s = base64_decode($s);
//
//    return $s;
//}
//$(function(){
//    var $cr=cr("Teste de uma frase bem dahora pra criptografar",$key);
//    window.alert($cr);
//    window.alert(dc($cr,$key));
//});




function crypt(str, key) {
    var m='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    str = base64_encode(str);
    for (var i = 0; i < key.length; i++) {
        var v = m.indexOf(key[i]);
        if (v > 1 && v < 10) {
            str = sh(str, v);
        }
        if (v > 11 && v < 20) {
            str = unsh(str, v - 10);
        }
        if (v > 21 && v < 35) {
            str = viginere(str, key, false);
        }
        if (v > 35 && v < 50) {
            str = unsh(str, v-35);
        }
//        if (v > 51) {
//            str+= randString(1);
//        }
    }
    return str;
}
function decrypt(str, key) {
    var m='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    for (var i = key.length; i >= 0; i--) {
        var v = m.indexOf(key[i]);
        if (v > 1 && v < 10) {
            str = unsh(str, v);
        }
        if (v > 11 && v < 20) {
            str = sh(str, v - 10);
        }
        if (v > 21 && v < 35) {
            str = viginere(str, key, true);
        }
        if (v > 35 && v < 50) {
            str = sh(str, v-35);
        }
//        if (v > 51) {
//            str = substr(str, 0, -1);
//        }
    }
    str = base64_decode(str);
    return str;
}

//var m='Hello world';
///var k=randString(32);
//var c=crypt(m,k);
//window.alert(c);
//window.alert(decrypt(c,k));





