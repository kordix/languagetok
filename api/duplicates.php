<?php

require('db.php');

$query = "DELETE FROM fragments
WHERE id NOT IN (
    SELECT MIN(id)
    FROM fragments
    GROUP BY tekst
);"




?>