<?php

//
//if (!empty($_POST['form'])) {
//    
//    if ($_POST['form']=='login') {
//        
//    } else if ($_POST['form']=='signup') {
//        
//    } else if ($_POST['form']=='zip') {
//        
//    }
//    
//    exit;
//}
//


//modelo de dados
$array=array(
    'type'=>'app', //app, site, desktop
    'system'=>'whitehats',
    'version'=>'1.0.0',
    'data'=>array(
        array(
            'table'=>'chat',
            'fields'=>array('from_id','to_id','msg'),
            'insert'=>array(
                array(1,2,'oi'),
                array(1,2,'tudo bem?')
            )
        ),
    )
);

if (!empty($_POST['table'])) {
    
    $redir='./';
    $array=explode('/',$_POST['actual']);
    
    $actual=$_POST['actual'];

    unset($_POST['actual']);
    unset($_POST['token-'.$_POST['table'].'-1']);
    unset($_POST['token-'.$_POST['table'].'-2']);

    if ($_POST['table']=='config') {
        $_POST['id']=1;
    }
    
//    pre($_POST);
//    pre($array);
        
    if ($array[1]=='contact') {
        $_SESSION['alert']='Mensagem enviada com sucesso!';
    } else if ($_POST['table']=='login') {
        $us=selectOne('*','user',"email='".$_POST['email']."'",'id DESC',1);
        if (!empty($us['id'])) {
            if ($us['pass']==md5($_POST['pass'])) {
                $_SESSION['id']=$us['id'];
                
                $redir=$root;
                $redir.=empty($array[1])?'':$array[1].'/';
                $redir.=empty($array[2])?'':$array[2].'/';
                $redir.=empty($array[3])?'':$array[3].'/';
                $redir.=empty($array[4])?'':$array[4].'/';
                $redir.=empty($array[5])?'':$array[5].'/';
                $redir=removeIfLastCharIs($redir,'/');
                
            } else {
                $_SESSION['alert']='Senha incorreta';
//                $redir='./login';
            }
        } else {
            $_SESSION['alert']='Email não encontrado';
//            $redir='./login';
        }
    } else if ($array[1]=='pass') {
        if (!empty($u['id'])) {
            if ($u['pass']==md5($_POST['pass'])) {
                update('user',array('pass'),array(md5($_POST['new-pass'])),$u['id']);
                $_SESSION['alert']='Senha alterada com sucesso!';
                unset($_SESSION['id']);
                $redir='./login';
                
            } else {
                $_SESSION['alert']='Senha incorreta';
                $redir='./pass';
            }
        } else {
            $_SESSION['alert']='Email não encontrado';
            $redir='./pass';
        }
    } else if ($array[1]=='adm') {
//        include 'backend/post/adm/'.$array[3].'.php';
        if (isset($_FILES['file']['tmp_name'])) {
            $path=$root.'file/';
            if (!file_exists($path)) {
                @mkdir($path,0777);
            }
            $ext=extension($_FILES['file']['name']);
            if ($ext=='jpeg') {
                $ext='jpg';
            }
            if ($ext=='php' or $ext=='php3') {
                $ext=$ext.'Junk';
            }
            $file=randString().'.'.$ext;
            while(file_exists($path.$file)) {
                $file=randString().'.'.$ext;
            }
            move_uploaded_file($_FILES['file']['tmp_name'], $path.$file);
            insert(
                'file',
                array('file','table','table_id'),
                array($file,$_POST['table'],$_POST['id'])
            );
        }
        
        $redir=$root;
        $redir.=empty($array[1])?'':$array[1].'/';
        $redir.=empty($array[2])?'':$array[2].'/';
        $redir.=empty($array[3])?'':$array[3].'/';
        $redir.=empty($array[4])?'':$array[4].'/';
        $redir.=empty($array[5])?'':$array[5].'/';
//        $redir=substr($redir,0,-1);
        
        $redir=removeIfLastCharIs($redir,'/');
        
        $redir=str_replace('/edit/','/list/',$redir);
        $redir=substr($redir,0,-1);
        if (!empty($array[4]) and is_numeric($array[4]) and $array[4]>0) {
            $redir=str_replace('/'.$array[4],'',$redir);
        }
    }
    if ($array[1]!='login') {
//        pre($redir);
//        pre($array);
//        pre($_POST);
        post($_POST); //a mágica ;)
    }
    echo '<script>window.location=\''.$redir.'\';</script>';
    exit;
}