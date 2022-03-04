<?php

function antiInjection($string) {
    return $string;
}

function toValue($post) {
//    $post = trim($post);
    $post = html_entity_decode($post);
//    $post = nl2br($post);
    $post = preg_replace("/\r\n|\r|\n/", '<br>', $post);
    $post = antiInjection($post);
    return $post;
}

function toHtml($post) {
    return $post;
}

function s($sql) {
    global $host, $user, $pass, $base;
    $conn = mysqli_connect($host, $user, $pass, $base);
    //se tiver erro da echo
    if (mysqli_error($conn)) {
        print_r(mysqli_error($conn));
        exit;
    }
    if (strpos('#' . $sql, 'SELECT') > 0 or strpos('#' . $sql, 'SHOW') > 0) {
        $array = array();
        $cols = mysqli_query($conn, $sql);
        if (isset($cols)) {
            while ($row = mysqli_fetch_assoc($cols)) {
                $array[] = $row;
            }
        }
        return $array;
    }
    mysqli_query($conn, $sql);
    
}

function f($sql) {
    $array = s($sql);
    if (is_array($array)) {
        if (isset($array[0])) {
            return $array[0];
        }
    }
}

function getTables() {
    global $tables, $base;
    if (isset($tables)) { //gambiarra pra não pegar muitos arrays (pensar numa forma de evitar isso :/)
        return $tables; //?
    }
    $result = s("SHOW TABLES");
    $tables = array();
    if (count($result) > 0) {
        foreach ($result as $row) {
            $tables[] = $row['Tables_in_' . $base];
        }
    }
    return $tables;
}

function getColumns($table) {
    create($table);
    $columns = array();
    $result = s("SHOW COLUMNS FROM `" . $table . "`");
    if (isset($result)) {
        if (count($result) > 0) {
            foreach ($result as $row) {
                $columns[] = $row['Field'];
            }
        }
    }
    return $columns;
}

function create($table) {
    $tables = getTables();
    if (!in_array($table, $tables)) {
        s("CREATE TABLE `" . $table . "` (`id` int(11) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
        s("ALTER TABLE `" . $table . "` ADD PRIMARY KEY(`id`);");
        s("ALTER TABLE `" . $table . "` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;");
    }
}

function alter($table, $column, $chartype = 'varchar', $size = '128') {
    create($table);
    $columns = getColumns($table);
    if (!in_array($column, $columns)) {
        foreach ($columns as $c) {
            if ($c != 'id') {
                $array[] = $c;
            }
        }
        if (isset($array[0])) {
            $last = end($array);
        } else {
            $last = 'id';
        }
        if ($chartype == 'datetime') {
//            if ($web == true) {
//            $sqlAlter = "ALTER TABLE `" . $table . "` ADD `" . $column . "` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' NULL AFTER `" . $last . "`;";
//            } else {
                $sqlAlter = "ALTER TABLE `" . $table . "` ADD `" . $column . "` datetime NULL DEFAULT NULL AFTER `" . $last . "`;";
//            }
        } else {
            $sz = "(" . $size . ")";
//            $sqlAlter = "ALTER TABLE `" . $table . "` ADD `" . $column . "` " . $chartype . $sz . " NOT NULL AFTER `" . $last . "`;";
            $df = '';
            if ($chartype == 'int' or $chartype == 'tinyint') {
                $df = '0';
            } else if ($chartype == 'decimal') {
                $df = '0.0';
            }
            $sqlAlter = "ALTER TABLE `" . $table . "` ADD `" . $column . "` " . $chartype . $sz . " NOT NULL DEFAULT '" . $df . "' AFTER `" . $last . "`;";
        }
        s($sqlAlter);
    }
}

function select($columns, $from = 'user', $where = '', $order = '', $limit = '') {
    $c = '*';
    if (is_array($columns)) {
        foreach ($columns as $column) {
            alter($from, $column);
            $c .= '`' . antiInjection($column) . '`,';
        }
        $c = substr($c, 0, -1);
    } else if (!empty($columns)) {
        $c = $columns;
    }
    $where = empty($where) ? '' : ' WHERE ' . $where . ' ';
    $order = empty($order) ? '' : ' ORDER BY ' . $order . ' ';
    $limit = empty($limit) ? '' : ' LIMIT ' . $limit . ' ';
    return s("SELECT " . $c . " FROM `" . antiInjection($from) . "`" . $where . $order . $limit);
}



function selectAsc($table){
    return select('*', $table, '', 'id ASC');
}
function selectDesc($table){
    return select('*', $table, '', 'id DESC');
}
function selectPublished($table){
    return select('*', $table, "published_at<='".date('Y-m-d H:i:s')."'", 'published_at DESC');
}

function images($table, $id) {
    return select('*', 'file', "`table`='" . $table . "' AND `table_id`='" . $id . "'", 'id DESC');
}

function selectOne($columns, $from = 'user', $where = '', $order = '', $limit = '') {
    $array = select($columns, $from, $where, $order, $limit);
    return isset($array[0]) ? $array[0] : false;
}

function user($id) {
    return selectOne('*','user','id='.$id);
}

function fetch($columns, $from = 'user', $where = '', $order = '', $limit = '') {
    $c = '*';
    if (is_array($columns)) {
        foreach ($columns as $column) {
            alter($from, $column);
            $c .= '`' . antiInjection($column) . '`,';
        }
        $c = substr($c, 0, -1);
    } else if (!empty($columns)) {
        $c = $columns;
    }
    $where = empty($where) ? '' : 'WHERE ' . $where;
    $order = empty($order) ? '' : 'ORDER BY ' . $order;
    $limit = empty($limit) ? '' : 'LIMIT ' . $limit;
    return f("SELECT " . $c . " FROM `" . antiInjection($from) . "`" . $where . $order . $limit);
}

function first($table) {
    return f("SELECT * FROM `" . antiInjection($table) . "` ORDER BY id ASC LIMIT 1");
}

function last($table) {
    return f("SELECT * FROM `" . antiInjection($table) . "` ORDER BY id DESC LIMIT 1");
}

$u=array();
if (!empty($_SESSION['id'])) {
    if (is_numeric($_SESSION['id']) and $_SESSION['id'] > 0) {
        $u = fetch('*', 'user', 'id=' . $_SESSION['id']);
    }
}

function insert($table, $columns, $values) {
    global $u;
    //TODO: falta tratar o array de arrays
    $c = $v = '';
    if (is_array($columns)) {
        foreach ($columns as $index => $column) {
            alter($table, $column);
            $c .= '`' . antiInjection($column) . '`,';
            if (is_array($values[$index])) {
                //terminar aqui
            } else {
                $v .= "'" . toValue($values[$index]) . "',";
            }
        }
        
        alter($table,'ip','varchar',15);
        alter($table,'user_id','int',11);
        alter($table,'created_at','datetime');
        alter($table,'updated_at','datetime');
        alter($table,'published_at','datetime');
        $c .= '`ip`,';
        $v .= "'" . $_SERVER['REMOTE_ADDR'] . "',";
        $c .= '`user_id`,';
        $v .= "'" . (empty($u['id'])?0:$u['id']) . "',";
        $c .= '`created_at`,';
        $v .= "'" . date('YmdHis') . "',";
        $c .= '`updated_at`,';
        $v .= "'" . date('YmdHis') . "',";
        
        $c = substr($c, 0, -1);
        $v = substr($v, 0, -1);
    }
    if (!empty($c)) {
        return s("INSERT INTO `" . antiInjection($table) . "` (" . $c . ") VALUES (" . $v . ")");
    }
}

function insertArray($table, $array) {
    $columns = $items = array();
    $c = 0;
    foreach ($array as $column => $item) {
        if (is_array($item)) {
            $items_ = array();
            foreach ($item as $column_ => $item_) {
                alter($table, $column_);
                if ($c == 0) {
                    $columns[] = $column_;
                }
                $items_[] = $item_;
            }
            $items[] = $items_;
        } else {
            alter($table, $column);
            $columns[] = $column;
            $items[] = $item;
        }
        $c++;
    }
    return insert($table, $columns, $items);
}

function update($table, $columns, $values, $id) {
    if (!empty($id) and is_numeric($id) and $id > 0) {
        $set = '';
        if (is_array($columns)) {
            $publish=false;
            foreach ($columns as $index => $column) {
                $set .= "`" . antiInjection($column) . "`='" . toValue($values[$index]) . "',";
                if ($column=='published_at') {
                    $publish=true;
                }
            }

            //adiciona data de atualização
            alter($table,'updated_at','datetime');
            alter($table,'published_at','datetime');
            $set .= "`updated_at`='" . date('Y-m-d H:i:s') . "',";
            
            if ($publish==false) {
                //limpa publicação
                $set .= "`published_at`=null,";
            }
            
            $set = substr($set, 0, -1);
        }
//        echo "UPDATE `" . antiInjection($table) . "` SET " . $set . " WHERE id=" . $id;
//        exit;
        s("UPDATE `" . antiInjection($table) . "` SET " . $set . " WHERE id=" . $id);
    }
}

function delete($table, $id) {
    if (!empty($id) and is_numeric($id) and $id > 0) {
        return s("DELETE FROM `" . antiInjection($table) . "` WHERE id=" . $id);
    }
}

function token() {
    //criar um token no db pro form
    $token = md5(time() . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9));
    insert('token', array('token', 'datetime'), array($token, date('Y-m-d H:i:s')));
}

function post($post) {
//    global $u;
    $columns = array();
    $values = array();
    foreach ($post as $field => $value) {
        if ($field != 'id' and $field != 'table') {
            $columns[] = $field;
            $values[] = $value;
            alter($post['table'], $field);
        }
    }
    
    

    if (empty($post['id'])) {
        $ret= insert($post['table'], $columns, $values);
        $type='created';
        $insert=selectOne('id',$post['table'],'','id DESC',1);
        $post['id']=$insert['id'];
    } else {
//        pre($post);
        $ret= update($post['table'], $columns, $values, $post['id']);
        $type='updated';
    }
    
//    pre($post);
    
    insertArray('action',array(
        'type'=>$type,
        'table'=>$post['table'],
        'table_id'=>$post['id'],
        'datetime'=>date('Y-m-d H:i:s'),
    ));
    
    return $ret;
}

//exemplos:
//select(array('name','email'),'user','id=1');
$config = fetch('*', 'config', 'id=1');
