<?php

require('db.php');

$counter = $_GET['counter'];
$id= $_GET['id'];

$sth = $dbh->prepare("update fragments set counter = ? where id = ?");

$sth->execute([$counter,$id]);
