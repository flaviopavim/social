<?php

//bot pra 'conversar'

$msg='';
if (rand(0,1)==0) {   
    if (rand(0,2)==0) {
        //trazer uma mensagem randomica do banco de dados
        $row=selectOne('*','chat','','RAND()');
        $msg=$row['msg'];
    } else {
        $array=array(
            'https://whitehats.com.br',
            'https://flaviopavim.com.br',
            'https://ironmaiden.com',
            'Hello World!',
            'Hello!!',
            'Hi',
            'Hye',
            'Oi, tudo bem?',
            'Oi, tudo bem!',
            'Olá mundo!',
            'Hey!',
            'Visite nosso site https://whitehats.com.br',
            'Meow',
        );
        $msg=$array[rand(0,count($array)-1)];
    } 
}

/*
//conectar com api pra receber mensagens
$url = 'https://api.telegram.org/bot'.$token.'/getUpdates';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'offset' => $offset,
    'limit' => 1
]));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$headers = array();
$headers[] = 'Content-Type: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$result = curl_exec($ch);
curl_close($ch);
$result=json_decode($result,true);


//verifica se existe mensagem
if (isset($result['result'][0]['message']['text'])) {
    $msg=$result['result'][0]['message']['text'];
}

//verifica se existe mensagem
if (isset($result['result'][0]['message']['photo'])) {
    $msg='Foto';
}
*/

if (!empty($msg)) {
    //criar uma mensagem aleatória

    $user_id=$_SESSION['to_id'];

    $user_id=rand(1,5);

    s("INSERT INTO chat (user_id,to_id,msg,`datetime`) VALUES 
        (".$user_id.",".$_SESSION['id'].",'".$msg."','".date('Y-m-d H:i:s')."')");

    s("UPDATE user SET `online` = '".date('Y-m-d H:i:s')."' WHERE id = ".$user_id);
}


