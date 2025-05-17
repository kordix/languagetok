<?php

require('db.php');

$sth = $dbh->prepare("update fragments set counter = 0");
$sth->execute();
