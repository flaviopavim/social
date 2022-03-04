<?php

include 'cn.php';       // configurações
include 'security.php'; // segurança
include 'db.php';       // funções do banco de dados
include 'alter.php';    // alter
include 'string.php';   // funções pra tratar strings
include 'url.php';      // ajeitar $_GET[0],$_GET[1]... de acordo com url
include 'view.php';     // variáveis pra montar o site
include 'crypt.php';    // criptografa entrada e saída de dados entre servidor e aplicação
include 'post.php';     // responsável pelos posts do site
//if (file_exists('backend/post/' . $path . '/' . $view . '.php')) {
//    include 'backend/post/' . $path . '/' . $view . '.php';
//}
include 'form.php';   // pra montar formulários mais rápido
include 'layout.php';   // responsável pelos posts do site