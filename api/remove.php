<?php

require('db.php');


$dane = json_decode(file_get_contents('php://input'), true);

if($dane['token'] !=='vzupoiuoiaugdsfhdcv'){
    echo 'invalid token';
    return;
}

$id = $dane['id'];

$sth = $dbh->prepare("delete from fragments where id = ?");

$sth->execute([$id]);
