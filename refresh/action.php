<?php

if (isset($_POST['data']) and is_array($_POST['data'])) {
    foreach($_POST['data'] as $table=>$array) {
        if ($table=='action') {
            foreach($array as $data) {
                if ($data['action']=='nick') {

                    s("UPDATE user SET nick='".$data['nick']."' WHERE id=".$_SESSION['id']);
                    
                } else if ($data['action']=='chat') {

                    if (!empty($data['to_id']) and empty($data['room_id'])) { //se for um usuário
                        $_SESSION['to_id']=$data['to_id'];
                        ?>
                        <script>
                           toID=<?php echo $_SESSION['to_id']; ?>
                        </script>
                        <?php
                    } else if (!empty($data['room_id']) and empty($data['to_id'])) { //se for uma sala
                        $_SESSION['room_id']=$data['room_id'];
                    } else if (!empty($data['room_id']) and 
                            !empty($data['to_id'])) { //se for um usuário dentro de uma sala
                        $_SESSION['to_id']=$data['to_id'];
                        $_SESSION['room_id']=$data['room_id'];
                    }

                    //TODO: desfazer essa gambiarra
                    s("DELETE FROM confirmation WHERE user_id=".$_SESSION['id']);

                    unset($_SESSION[$_POST['session'].'_chat']);

                    ?>
                    <script>
                        $(function(){
                            $('#msg').focus();
                        });
                    </script>
                    <?php

                } else if ($data['action']=='signup') {
                    //faz cadastro

                    $login=$data['login'];

                    $user_=selectOne('*','user','login="'.$login.'"');
                    if (empty($user_['id'])) {

                        insert('user',array('login','pass'),array(
                            $data['login'],
                            md5($data['pass'])
                        ));


                        $user_=selectOne('*','user','login="'.$login.'"');
                        $_SESSION['id']=$user_['id'];
                        ?>
                        <script>
                            alert('Usuário cadastrado com sucesso!');
                        </script>
                        <?php
                    } else {
                        ?>
                        <script>
                            alert('Usuário já existe');
                        </script>
                        <?php
                    }



                } else if ($data['action']=='login') {
                    //faz o login do usuário
                    //muda ids do usuário de mensagens quando logar

                    $login=$data['login'];

                    $user_=selectOne('*','user','login="'.$login.'"');
                    if (!empty($user_['id'])) {
                        if ($user_['pass']==md5($data['pass'])) {
                            $_SESSION['id']=$user_['id'];
                            ?>
                            <script>
                                alert('Logado');
                            </script>
                            <?php
                        } else {
                            ?>
                            <script>
                                alert('Senha incorreta');
                            </script>
                            <?php
                        }
                    } else {
                        ?>
                        <script>
                            alert('Usuário não encontrado');
                        </script>
                        <?php
                    }

                    

                    //s("UPDATE chat SET user_id='' WHERE user_id=''");
                    //s("UPDATE chat SET to_id='' WHERE to_id=''");
                } else if ($data['action']=='logout' or $data['action']=='exit') {

                    //insere novo usuário e criar uma sessão pra ele
                    s("INSERT INTO user (id) VALUES (NULL)");
                    $user_=f("SELECT id FROM user ORDER BY id DESC LIMIT 1");
                    $_SESSION['id']=$user_['id'];

                    ?>
                    <script>
                        $('.msg-temp').remove();
                        $('#chat-container').html('');
                        $('#left-body').html('Reseting...');
                    </script>
                    <?php
  
                } else if ($data['action']=='pass') {
                    //muda a senha
                } else if ($data['action']=='reset' or $data['action']=='truncate') {
                    
                    s("TRUNCATE TABLE user");
                    s("TRUNCATE TABLE chat");
                    s("TRUNCATE TABLE session");
                    s("TRUNCATE TABLE confirmation");

                    s("INSERT INTO user (nick) VALUES ('adm'),('kicko'),('leo'),('cego'),('leni')");

                    //s("DELETE FROM user WHERE id='".$_SESSION['id']."'");
                   // s("DELETE FROM chat WHERE user_id='".$_SESSION['id']."' OR to_id='".$_SESSION['id']."'");
                    //s("DELETE FROM `session` WHERE user_id='".$_POST['session']."'");
                    //s("DELETE FROM confirmation WHERE user_id='".$_SESSION['id']."'");

                    unset($_SESSION);
                    s("INSERT INTO user (id) VALUES (NULL)");
                    $user_=f("SELECT id FROM user ORDER BY id DESC LIMIT 1");
                    $_SESSION['id']=$user_['id'];
                    ?>
                    <script>
                        $('.msg-temp').remove();
                        $('#chat-container').html('');
                        $('#left-body').html('Reseting...');
                    </script>
                    <?php
                } else {
                    ?>
                    <script>
                        alert('Comando não encontrado');
                    </script>
                    <?php
                }
                ?>
                <script>
                    data.data.action=[];
                </script>
                <?php
                if ($data['action']=='nick') {
                    ?>
                    <script>
                        chat(<?php echo $_SESSION['to_id']; ?>);
                        
                    </script>
                    <?php
                } 
            }
        }
    }
}