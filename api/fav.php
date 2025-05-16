<?php

require('db.php');

$id= $_GET['id'];

$sth = $dbh->prepare("update fragments set fav = 1 where id = ?");

$sth->execute([$id]);
