<?php

$users=s("
SELECT 
    u.*,
    (
        IF(
            EXISTS(SELECT id FROM chat WHERE 
                (user_id=u.id AND to_id=".$_SESSION['id'].") OR 
                (to_id=u.id AND user_id=".$_SESSION['id'].")
            ),
            (SELECT msg FROM chat WHERE 
                (user_id=u.id AND to_id=".$_SESSION['id'].") OR 
                (to_id=u.id AND user_id=".$_SESSION['id'].")
                    ORDER BY id DESC LIMIT 1),
            ''
        )
    ) AS last_msg,
    (
        IF(
            EXISTS(SELECT id FROM chat WHERE 
                (user_id=u.id AND to_id=".$_SESSION['id'].") OR 
                (to_id=u.id AND user_id=".$_SESSION['id'].")
            ),
            (SELECT md5 FROM chat WHERE 
                (user_id=u.id AND to_id=".$_SESSION['id'].") OR 
                (to_id=u.id AND user_id=".$_SESSION['id'].")
                    ORDER BY id DESC LIMIT 1),
            ''
        )
    ) AS last_msg_md5,
    (
        IF(
            EXISTS(SELECT id FROM chat WHERE 
                (user_id=u.id AND to_id=".$_SESSION['id'].") OR 
                (to_id=u.id AND user_id=".$_SESSION['id'].")
            ),
            (SELECT user_id FROM chat WHERE 
                (user_id=u.id AND to_id=".$_SESSION['id'].") OR 
                (to_id=u.id AND user_id=".$_SESSION['id'].")
                    ORDER BY id DESC LIMIT 1),
            0
        )
    ) AS last_msg_user_id,
    (
        IF(
            EXISTS(SELECT id FROM chat WHERE 
                (user_id=u.id AND to_id=".$_SESSION['id'].") OR 
                (to_id=u.id AND user_id=".$_SESSION['id'].")
            ),
            (SELECT `datetime` FROM chat WHERE 
                (user_id=u.id AND to_id=".$_SESSION['id'].") OR 
                (to_id=u.id AND user_id=".$_SESSION['id'].")
                    ORDER BY id DESC LIMIT 1),
            0
        )
    ) AS last_msg_datetime
FROM 
    user u
WHERE 
    u.`online`>='".date('Y-m-d H:i:s',strtotime('-5 minutes'))."' AND
    u.id!=".$_SESSION['id']."
ORDER BY last_msg_datetime DESC
LIMIT 10
");

function limitString($str, $chars=50) {
    if (strlen($str)>$chars) {
        return substr($str,0,$chars)."...";
    }
    return $str;
}

$html='';
foreach($users as $row) {

    $date='';
    if (!empty($row['last_msg_datetime'])) {
        $date=date('H:i',strtotime($row['last_msg_datetime']));
    }

    $msg=$row['last_msg_user_id']==$_SESSION['id']?'You: ':'';
    $msg.=($row['last_msg_md5']==''?limitString($row['last_msg'],28):'<i class="glyphicon glyphicon-lock"></i>');


    $html.='
    <div class="left-link" onclick="chat(\\\''.$row['id'].'\\\');">
        <img src="https://www.w3schools.com/howto/img_avatar.png">
        <div class="left-link-body">
            <div class="left-link-body-title">'.nick($row).'</div>
            <div class="left-link-body-user_id">'.$row['id'].'</div>
            <div class="left-link-body-md5">'.$row['last_msg_md5'].'</div>
            <div class="left-link-body-msg">'.$msg.'</div>
            <div class="left-link-body-text"></div>
            <div class="left-link-body-footer">
                '.$date.'
            </div>
        </div>
    </div>';
}

//remover quebras de linhas
$html=preg_replace('/\s+/', ' ', $html);


$us=f("SELECT * FROM user WHERE id=".$_SESSION['to_id']);



?>
<script>
    $('#left-body').html('<?php echo $html; ?>');
    $('.left-link-body').each(function(){
        var md5_=$(this).children('.left-link-body-md5').html();
        var msg=$(this).children('.left-link-body-msg').html();
        var user_id=Number($(this).children('.left-link-body-user_id').html());

        try {
            if (typeof key[user_id]!=='undefined' && md5_!=='' && key[user_id]!=='' && md5_==md5(key[user_id])) {
                msg=decrypt(msg,key[user_id]);
            }
        } catch (e) {
            console.log(e);
        }

        $(this).children('.left-link-body-text').html(msg)

        
        $('#center-top-img').attr('src','https://www.w3schools.com/howto/img_avatar.png');
        $('#center-top-title').html('<?php echo nick($us); ?>');
        $('#center-top-subtitle').html('Data e hora?');

        $('#avatar').attr('src','https://www.w3schools.com/howto/img_avatar.png');
        $('#name').html('<?php echo nick($us); ?>');
    });
</script>