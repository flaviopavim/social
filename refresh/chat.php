<?php
//verifica se a session existe, senão cria
$one=selectOne("id","session","session='".$_POST['session']."'");
$firstRefresh=true;
if (empty($one['id'])) {
    s("INSERT INTO session (session,`datetime`) VALUES ('".$_POST['session']."','".date('Y-m-d H:i:s')."')");
    if (empty($_SESSION['id'])) {
        //insere novo usuário e criar uma sessão pra ele
        s("INSERT INTO user (id) VALUES (NULL)");
        $user_=f("SELECT id FROM user ORDER BY id DESC LIMIT 1");
        $_SESSION['id']=$user_['id'];
    }

} else {
    $firstRefresh=false;
}

//busca usuario logado
//$one=selectOne("id","user","uuid='".$_POST['uuid']."'");
//$_SESSION['id']=empty($one['id'])?0:$one['id'];

//atualiza ids que foram eviados ao app e confirmados pelo app
foreach($_POST['confirmed'] as $table=>$arrayIDs) {
    foreach($arrayIDs as $id) {
        //echo $table;
        alter($table,'confirmed','tinyint',1);
        update($table,array('confirmed'),array(1),$id);
    }
}

$confirmed=array();
if (isset($_POST['data']) and is_array($_POST['data'])) {
    foreach($_POST['data'] as $table=>$array) {
        if ($table=='chat') {
            foreach($array as $data) {
                //verifica se ja foi enviado
                $one=selectOne("id","confirmation","
                    `table`='".$table."' AND 
                    local_id=".$data['local_id']." AND 
                    `user_id`=".$_SESSION['id']." AND 
                    uuid='".$_POST['uuid']."' AND 
                    session='".$_POST['session']."'
                ");
                //se não existir, insere
                if (empty($one['id'])) {
                    //insert into confirmation
                    $sql="INSERT INTO confirmation (
                        `table`,local_id,user_id,uuid,session
                    ) VALUES (
                        '".$table."',".$data['local_id'].",".$_SESSION['id'].",'".$_POST['uuid']."','".$_POST['session']."'
                    )";
                    s($sql);

                    //insert into chat

                    $sql="INSERT INTO chat (
                        user_id,to_id,md5,msg,datetime
                    ) VALUES (
                        ".$_SESSION['id'].",".$data['to_id'].",'".$data['md5']."','".$data['msg']."','".date('Y-m-d H:i:s')."'
                    )";
                    s($sql);

                }
                //adiciona os dados confirmados
                $confirmed[$table][]=$data['local_id'];
            }
        }
        
    }
}

//base do select de mensagens
$select="
SELECT
    c.*
FROM
    chat c
WHERE
    (
        (c.user_id=".$_SESSION['id']." AND c.to_id=".$_SESSION['to_id'].") OR
        (c.to_id=".$_SESSION['id']." AND c.user_id=".$_SESSION['to_id'].")
    ) AND
    NOT EXISTS(
        SELECT 
            id 
        FROM 
            confirmation 
        WHERE 
            `table`='chat' AND 
            table_id=c.id AND
            `user_id`=".$_SESSION['id']." AND 
            uuid='".$_POST['uuid']."' AND 
            session='".$_POST['session']."'
    ) 
";
/*
 AND
    NOT EXISTS(
        SELECT 
            id 
        FROM 
            confirmation 
        WHERE 
            `table`='chat' AND 
            table_id=c.id AND
            `user_id`=".$_SESSION['id']." AND 
            uuid='".$_POST['uuid']."' AND 
            session='".$_POST['session']."'
    )
    */

//primeiro carregamento das mensagens do chat
if (!isset($_SESSION[$_POST['session'].'_chat'])) {
    $_SESSION[$_POST['session'].'_chat']=1;
    $sql=$select."ORDER BY id DESC LIMIT 20";
    $html='';
    $arrayChat=s($sql);
    if (isset($arrayChat) and is_array($arrayChat)) {
            foreach ($arrayChat as $field=>$chat) { //pega o ultimo id
                $_SESSION[$_POST['session'].'_chat']=$chat['id'];
            }
            $arrayChat=array_reverse($arrayChat);
            foreach ($arrayChat as $field=>$chat) {
                $us=f("SELECT * FROM user WHERE id=".$chat['user_id']);
                $html.='
                    <div class="msg baloon-'.(($chat['user_id']==$_SESSION['id'])?'right':'left').'">
                        <div'.(($chat['user_id']==$_SESSION['id'])?' style="cursor: pointer;" onclick="$(\\\'#msg\\\').val(\\\'/nick \\\').focus()"':'').' class="msg-header"><div class="msg-title">'.nick($us).'</div></div>
                        <div class="crypted'.(!empty($chat['md5'])?' '.$chat['md5']:'').'">'.$chat['msg'].'</div>
                        <div class="msg-body'.(!empty($chat['md5'])?' msg-'.$chat['md5']:'').'"></div>
                        <div class="msg-footer">'.date('H:i',strtotime($chat['datetime'])).'</div>
                    </div>
                    ';
                //insert into confirmation
                $sql="INSERT INTO confirmation (
                    `table`,table_id,user_id,uuid,session
                ) VALUES (
                    'chat',".$chat['id'].",".$_SESSION['id'].",'".$_POST['uuid']."','".$_POST['session']."'
                )";
                s($sql);
        }
    }
    $html=preg_replace('/\s+/', ' ', $html);
    ?>
    <script>
        $('#chat-container').html('<?php echo $html; ?>');
        decryptChat();
        if (roll) {
            $('#center').scrollTop($('#center')[0].scrollHeight);
        }
        refreshing=false;
        secs=7;
        data.data.chat=[];
    </script>
    <?php
} else {
    //depois do primeiro carregamento, só busca novas mensagens
    $sql=$select." AND c.id>".$_SESSION[$_POST['session'].'_chat'];
    $html='';
    $arrayChat=s($sql);
    if (isset($arrayChat) and is_array($arrayChat)) {
        foreach ($arrayChat as $field=>$chat) {
            $us=f("SELECT * FROM user WHERE id=".$chat['user_id']);
            $html.='
            <div class="msg baloon-'.(($chat['user_id']==$_SESSION['id'])?'right':'left').'">
                <div'.(($chat['user_id']==$_SESSION['id'])?' style="cursor: pointer;" onclick="$(\\\'#msg\\\').val(\\\'/nick \\\').focus()"':'').' class="msg-header"><div class="msg-title">'.nick($us).'</div></div>
                <div class="crypted'.(!empty($chat['md5'])?' '.$chat['md5']:'').'">'.$chat['msg'].'</div>
                <div class="msg-body'.(!empty($chat['md5'])?' msg-'.$chat['md5']:'').'"></div>
                <div class="msg-footer">'.date('H:i',strtotime($chat['datetime'])).'</div>
            </div>
            ';
            s("INSERT INTO confirmation (
                `table`,table_id,user_id,uuid,session
            ) VALUES (
                'chat',".$chat['id'].",".$_SESSION['id'].",'".$_POST['uuid']."','".$_POST['session']."'
            )");
        }
    }
    $html=preg_replace('/\s+/', ' ', $html);
    if (!empty($html)) {
        ?>
        <script>
            $('.msg-temp').remove();
            $('#chat-container').append('<?php echo $html; ?>');
            decryptChat();
            if (roll) {
                $('#center').scrollTop($('#center')[0].scrollHeight);
            }
            refreshing=false;
            secs=7;
            data.data.chat=[];
        </script>
    <?php }
}