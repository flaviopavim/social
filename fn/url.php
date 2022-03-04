<?php

//NO SITE:

//https://nomedosite.com.br/produtos/todos/3

//0 -> produtos -> pagina principal (view)
//1 -> todos    -> categoria, ou todos
//2 -> 3        -> paginação

//0 -> busca -> pagina principal (view)
//1 -> tags  -> texto digitado
//2 -> 3     -> paginação

//NO ADM:

//https://nomedosite.com.br/adm/list/produto/todos/3

//0 -> adm     -> adm
//1 -> list    -> pagina principal (view)
//2 -> produto -> tabela listada
//3 -> todos   -> categoria, ou todos
//4 -> 3       -> paginação

$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
$parse = parse_url($actual_link);
$domain = $parse['host'];
$site_url=substr($systemUrl,0,-1);

$x=explode($site_url.'/',$actual_link);
$root='./';
if (isset($x[1])) {
    $get=explode('/',$x[1]);
    if (isset($get)) {
        foreach($get as $f=>$v) {
            if ($f>0) {
                $root.='../';
            }
            $_GET[$f]=$v;
        }
    }
}


insert('access',array('0','1','2','3','4','5'),array(
    (empty($_GET[0])?'':$_GET[0]),
    (empty($_GET[1])?'':$_GET[1]),
    (empty($_GET[2])?'':$_GET[2]),
    (empty($_GET[3])?'':$_GET[3]),
    (empty($_GET[4])?'':$_GET[4]),
    (empty($_GET[5])?'':$_GET[5])
));


if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') or $domain=='localhost') {
} else {
    ?>
    <script>window.location='<?php echo str_replace('http://','https://',$actual_link); ?>';</script>
    <?php
    exit;
}
if (strpos($actual_link,'/?fbclid=')>0) {
    $x=explode('/?fbclid=',$actual_link);    
    ?>
    <script>window.location='<?php echo $x[0]; ?>';</script>
    <?php
    exit;
}
if (strpos($actual_link,'?fbclid=')>0) {
    $x=explode('?fbclid=',$actual_link);    
    ?>
    <script>window.location='<?php echo $x[0]; ?>';</script>
    <?php
    exit;
}
if (strpos($actual_link,'/?fb_comment_id=')>0) {
    $x=explode('/?fb_comment_id=',$actual_link);    
    ?>
    <script>window.location='<?php echo $x[0]; ?>';</script>
    <?php
    exit;
}
if (strpos($actual_link,'?fb_comment_id=')>0) {
    $x=explode('?fb_comment_id=',$actual_link);    
    ?>
    <script>window.location='<?php echo $x[0]; ?>';</script>
    <?php
    exit;
}
if (strrpos($actual_link,'www.')>0) {
    ?>
    <script>window.location='<?php echo str_replace('www.','',$actual_link); ?>';</script>
    <?php
    exit;
}


if (!empty($_GET[0]) and $_GET[0]=='adm' and empty($_SESSION['id'])) {
    header('Location: '.$site_url.'/login');
    exit;
}
//if (!empty($_GET[0]) and $_GET[0]=='user' and empty($_SESSION['id'])) {
//    header('Location: '.$site_url.'/404');
//    exit;
//}
if (!empty($_GET[0]) and $_GET[0]=='adm') {
    if (!empty($_GET[1]) and $_GET[1]=='publish') {
        
        $row=selectOne('published_at','content','id='.$_GET[3],'id DESC',1);
        $published_at='null';
        if (empty($row['published_at']) or $row['published_at']=='0000-00-00 00:00:00' or $row['published_at']=='null') {
            $published_at="'".date('Y-m-d H:i:s')."'";
        }
//        update('content',array('published_at'),array($published_at), $_GET[3]);
        s("UPDATE `content` SET `published_at`=".$published_at." WHERE id=".$_GET[3]);
        echo '<script>window.location=\'../../list/' . $_GET[2] . '\';</script>';
        exit;
    }
}

//if (!empty($_GET[0]) and $_GET[0] == 'adm' and empty($_SESSION['id'])) {
//    echo "<script>window.location='".$root."restrict';</script>";
//    exit;
//}


if (!empty($_GET[0]) and $_GET[0] == 'cart') {
    if (!empty($_GET[1]) and is_numeric($_GET[1]) and $_GET[1] > 0) {
        //adiciona ítem ao carrinho
        $fetch = selectOne('id', 'cart', 'product_id='.$_GET[1].' AND ip=\''.$_SERVER['REMOTE_ADDR'].'\'');
        if (empty($fetch['id'])) {
            post(array(
                'table' => 'cart',
                'product_id' => $_GET[1],
                'amount' => 1,
//                'ip' => $_SERVER['REMOTE_ADDR'],
//                'datetime'=>date('YmdHis')
            ));
        }
        
//        echo '<pre>';
//        print_r($fetch);
//        echo '</pre>';
//        exit;
        
        echo '<script>window.location=\'../cart\';</script>';
        exit;
    }
}
if (!empty($_GET[0]) and $_GET[0] == 'logout') {
    session_destroy();
    echo '<script>window.location=\'./\';</script>';
    exit;
}
if (!empty($_GET[1]) and $_GET[1] == 'delete') {
    delete($_GET[2], $_GET[3]);
    echo '<script>window.location=\'../../list/' . $_GET[2] . '\';</script>';
    exit;
}