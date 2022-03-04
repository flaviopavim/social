<?php

function formTop($table, $file = false) {
    global $root;
    $actual = './';
    foreach ($_GET as $get) {
        $actual .= $get . '/';
    }
    $id = '';
    if (!empty($_GET[3]) and is_numeric($_GET[3]) and $_GET[3] > 0) {
        $id = '<input type="hidden" name="id" value="' . $_GET[3] . '">';
    }
    return '
        <form ' . ($file ? 'enctype="multipart/form-data" ' : '') . 'action="' . $root . '" method="post">
            <input type="hidden" name="table" value="' . $table . '">
            ' . $id . '
            <input type="hidden" name="actual" value="' . $actual . '">
            <input type="hidden" name="token-' . $table . '-1" value="' . $_SESSION['token-' . $table] = md5(time()
                    . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9)
                    . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9)
            ) . '">
            <input type="hidden" name="token-' . $table . '-2" value="' . token() . '">
    ';
//            <input type="hidden" name="token-'.$table.'-3" value="'.$_SESSION['token'].'">
}

function input($title, $name, $type = 'text', $value = '') {
    if ($type=='money') {
        $type='text';
    }
    return '
        <div class="form-group">
            <label>' . $title . '</label>
            <input class="form-control" type="' . $type . '" name="' . $name . '" value="' . $value . '">
        </div>
    ';
}

function textarea($title, $name, $value = '') {
    return '
        <div class="form-group">
            <label>' . $title . '</label>
            <textarea class="form-control" name="' . $name . '">' . $value . '</textarea>
        </div>
    ';
}

function ckeditor($title, $name, $value = '') {
    return '
        <div class="form-group">
            <label>' . $title . '</label>
            <textarea id="input-' . $name . '" class="form-control ckeditor" name="' . $name . '">' . $value . '</textarea>
        </div>
    ';
}

function button($title, $btn = 'success', $type = '') {
    return '<button class="btn btn-' . $type . ' btn-' . $btn . ' right" type="submit">' . $title . '</button><div class="br"></div>';
}

function getForm($array, $data) {
    global $root;

    $return = '';
    $content = '';

    $array['type'] = empty($array['type']) ? 'text' : $array['type'];
    $array['title'] = empty($array['title']) ? ucfirst($array['name']) : $array['title'];

    if ($array['type'] == 'text' or $array['type'] == 'email' or $array['type'] == 'money') {
        $content = input($array['title'], $array['name'], $array['type'], empty($data[$array['name']]) ? '' : $data[$array['name']]);
    }
    if ($array['type'] == 'textarea') {
        $content = textarea($array['title'], $array['name'], empty($data[$array['name']]) ? '' : $data[$array['name']]);
    }
    if ($array['type'] == 'ckeditor') {
        $content = ckeditor($array['title'], $array['name'], empty($data[$array['name']]) ? '' : $data[$array['name']]);
    }
    if ($array['type'] == 'image') {
        $content = '
            <img id="image-' . $array['name'] . '" class="image-upload img-responsive" src="' . $root . 'backend/php/im.php?url=../../file/' . (empty($data[$array['name']]) ? '' : $data[$array['name']]) . '">
            <input id="file-' . $array['name'] . '" name="' . $array['name'] . '" type="file" style="display: none;">
            <input id="hidden-' . $array['name'] . '" name="' . $array['name'] . '" type="hidden" value="' . (empty($data[$array['name']]) ? '' : $data[$array['name']]) . '">
            <div class="br15"></div>
        ';
    }
    if ($array['type'] == 'group') {
        $content_='';
        foreach($array['group'] as $item) {
            
            $content_.=getForm($item,array());
//            $content_.= '
//                hello
//            ';
        }
        
        $values='';
        for($i=1;$i<5;$i++) {
            $values.='
            <tr>
                <td>'.$i.'</td>
                <td>Descrição</td>
                <td>Preto</td>
                <td>M</td>
                <td>R$ 12,35</td>
                <td>
                    <i class="glyphicon glyphicon-remove"></i>
                </td>
            </tr>
            ';
        }
        
        $content = '
            
            <div style="padding: 15px; background-color: #fff; display: block;">

                <div class="br15"></div>

                <div class="row">
                <div class="col-md-10">
                <div class="row">
                '.$content_.'
                </div>
                </div>
                <div class="col-md-2">
                    <a style="margin-top: 25px;" class="btn btn-default btn-block">Adicionar</a>
                </div>
                </div>
                <div class="br15"></div>
                
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Descrição</th>
                            <th>Cor</th>
                            <th>Tamanho</th>
                            <th>Preço</th>
                            <th>
                                <i class="glyphicon glyphicon-remove"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        '.$values.'
                    </tbody>
                </table>

            </div>
            <div class="br15"></div>
        ';
        
    }
    if (!empty($array['size']) and is_numeric($array['size']) and $array['size'] > 0) {
        
    } else {
        $array['size'] = 12;
    }
    $return .= '
        <div class="col-md-' . $array['size'] . '">
            ' . $content . '
        </div>
    ';
    return $return;
}

function formCrud($crud, $data = array()) {
    $return = '';
    $return .= formTop($crud['table']);



    $return .= '<div class="row">';
    foreach ($crud['data'] as $array) {
        
        $char='text';
        $size=128;
        if (!empty($array['type'])) {
            if ($array['type']=='textarea') {
                $char='text';
                $size=1024;
            }
            if ($array['type']=='ckeditor') {
                $char='text';
                $size=1024000;
            }
        }
        
        alter($crud['table'],$array['name'],$char,$size);
        $return .= getForm($array, $data);
    }
    $return .= '</div>';
    $return .= button(empty($_GET[3]) ? $crud['insert'] : $crud['update']);
    $return .= formBottom();
    return $return;
}

function formBottom() {
    return '</form>';
}

//
//$formLogin=array(
//    'name'=>'login',
//    'data'=>array(
//        array(
//            'title'=>'Email',
//            'name'=>'email',
//            'type'=>'text',
//            'value'=>'',
//        ),
//        array(
//            'title'=>'Email',
//            'name'=>'email',
//            'type'=>'password',
//            'value'=>'',
//        ),
//    ),
//    'button'=>'Entrar'
//);
//
//function form($arrayForm) {
//    
//    $form='';
//    
//    foreach($arrayForm as $array) {
//        $form.=input($array['title'],$array['name'],$array['type'],$array['value']);
//    }
//    
//    return formTop().$form.formBottom();
//}
//








