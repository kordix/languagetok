<?php

require('db.php');


$dane = json_decode(file_get_contents('php://input'), true);

if($dane['token'] !=='vzupoiuoiaugdsfhdcv'){
    echo 'invalid token';
    return;
}

$id = $dane['id'];

$sth = $dbh->prepare("select * from fragments where id = ?");
$sth->execute([$id]);

$rows = $sth->fetchAll(PDO::FETCH_ASSOC);

$file = $rows[0]['filename'];
unlink($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'mp3'.DIRECTORY_SEPARATOR.$file);

$sth = $dbh->prepare("delete from fragments where id = ?");

if (file_exists($plik)) {
    unlink($plik);
    echo "Plik został usunięty.";
} else {
    echo "Plik nie istnieje.";
}

$sth->execute([$id]);
