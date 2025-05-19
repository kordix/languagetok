<?php


function randomString($length = 5)
{
    $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, strlen($characters) - 1)];
    }
    return $randomString;
}

require('db.php');

$id1 = $_GET['id1'];
$id2 = $_GET['id2'];


$sth = $dbh->prepare("SELECT * FROM fragments where id in (?,?)");

$sth->execute([$id1,$id2]);

$rows = $sth->fetchAll(PDO::FETCH_ASSOC);


$file1 = $rows[0]['filename'];
$file2 = $rows[1]['filename'];
echo $file1;
$asdf = randomString();
$output = $asdf.'.mp3';

$cmd = "ffmpeg -i \"$file1\" -i \"$file2\" -filter_complex \"[0:a][1:a]concat=n=2:v=0:a=1[outa]\" -map \"[outa]\" \"$output\"";
echo $cmd;
shell_exec($cmd);

$tekst = $rows[0]['tekst'].' '.$rows[1]['tekst'];

$sth = $dbh->prepare("update fragments set filename = ?, tekst = ? where id = ?");
$sth->execute([$output,$tekst,$id1]);

unlink(__DIR__.$file2);
$sth = $dbh->prepare("delete from fragments where id = ?");
$sth->execute([$id2]);


?>
