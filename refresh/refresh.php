<?php
//session_start();
/*

Recebe do frontend:
    View
    Mensagens

Envia pro backend:

    Feed
    Notificações
    Mensagens (Salas e usuários)
*/

include '../fn/index.php';




alter('user','uuid');
alter('user','login');
alter('user','nick');
alter('user','online','datetime');
alter('user','pass','varchar',32);

alter('confirmation','table','varchar',32);
alter('confirmation','table_id','int',11);
alter('confirmation','confirmed','tinyint',1);
alter('confirmation','local_id','int',11);
alter('confirmation','user_id','int',11);
alter('confirmation','uuid');
alter('confirmation','session');

//alter('chat','session');
//alter('chat','local_id','int',11);
alter('chat','user_id','int',11);
alter('chat','to_id','int',11);
alter('chat','md5','varchar',32);
alter('chat','msg','text',1024000);
alter('chat','datetime','datetime');

//cria os alters da session
alter('session','id','int',11);
alter('session','session','varchar',32);
alter('session','datetime','datetime');

if (!empty($_SESSION['id'])) {
    s("UPDATE user SET `online` = '".date('Y-m-d H:i:s')."' WHERE id = ".$_SESSION['id']);
}

//retornos
include 'action.php';
include 'view.php';
include 'feed.php';
include 'notification.php';
include 'chatbot.php';
include 'chat.php';
include 'left.php';

?>
<script>
    datetime='<?php echo date('Y-m-d H:i:s'); ?>';
</script>