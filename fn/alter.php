<?php
create('config');
create('note');
alter('user', 'email');
alter('user', 'pass', 'varchar', 32);
create('content');
create('page');
create('video');
create('product');
alter('cart','product_id','int',11);
alter('cart','ip','varchar',15);


$f=f("SELECT * FROM user WHERE id=1");
if (empty($f['id'])) {
    s("INSERT INTO user (email,pass) VALUES ('flavio@whitehats.com.br','".md5('123')."')");
}
$f=f("SELECT * FROM user WHERE id=2");
if (empty($f['id'])) {
    s("INSERT INTO user (email,pass) VALUES ('leo@whitehats.com.br','".md5('123')."')");
}
$f=f("SELECT * FROM user WHERE id=3");
if (empty($f['id'])) {
    s("INSERT INTO user (email,pass) VALUES ('cego@whitehats.com.br','".md5('123')."')");
}
$f=f("SELECT * FROM user WHERE id=4");
if (empty($f['id'])) {
    s("INSERT INTO user (email,pass) VALUES ('leni@whitehats.com.br','".md5('123')."')");
}