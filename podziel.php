<?php

// Zakładamy, że masz instancję PDO
 // <- tu trzymasz np. $dbh

$sep = DIRECTORY_SEPARATOR;

require_once 'db.php';


$subFile = __DIR__ . $sep . 'output' . $sep . 'video.en-GB.srt';
$audioFile = __DIR__ . $sep . 'output' . $sep . 'audio.mp3';
$outputDir = __DIR__ . $sep . 'mp3';

if (!file_exists($outputDir)) {
    mkdir($outputDir, 0777, true);
}

if (!file_exists($subFile)) {
    die("Brak pliku z napisami: $subFile\n");
}

$lines = file($subFile);
$entries = [];
$current = [];

foreach ($lines as $line) {
    $line = trim($line);
    if (preg_match('/^\d+$/', $line)) {
        if ($current) {
            $entries[] = $current;
        }
        $current = ['index' => $line];
    } elseif (strpos($line, '-->') !== false) {
        [$start, $end] = explode(' --> ', $line);
        $current['start'] = $start;
        $current['end'] = $end;
    } elseif ($line !== '') {
        $current['text'][] = $line;
    }
}
if ($current) {
    $entries[] = $current;
}

$insertStmt = $dbh->prepare("INSERT INTO fragments (filename, tekst) VALUES (:filename, :tekst)");

$counter = 0;
foreach ($entries as $i => $entry) {
    $counter++;
    if($counter > 20){
       # return;
    }
    $start = timeToSeconds($entry['start']);
    $duration = timeToSeconds($entry['end']) - $start;
    if ($duration <= 0) {
        continue;
    }

    $file = $_POST['file'];

    $text = implode(' ', $entry['text']);
    $filename = "$file_$i.mp3";
    $outputPath = $outputDir . $sep . $filename;

    // Wywołanie ffmpeg
    echo $start;
    echo '   ';
    echo $duration;
    $cmd = "ffmpeg -y -i \"$audioFile\" -ss $start -t $duration -acodec copy \"$outputPath\" 2>&1";
    shell_exec($cmd);

    #Wstawiamy do bazy danych
    $insertStmt->execute([
        ':filename' => $filename,
        ':tekst' => $text
    ]);

    echo "Zapisano fragment: $filename\n";
}

echo "Zrobione.\n";

// ========================

function timeToSeconds($time)
{
    // Zamień przecinek na kropkę (np. 00:01:23,456 → 00:01:23.456)
    $time = str_replace(',', '.', $time);

    // Parsuj h:m:s.ms
    if (preg_match('/(\d+):(\d+):(\d+(?:\.\d+)?)/', $time, $matches)) {
        $h = (int)$matches[1];
        $m = (int)$matches[2];
        $s = (float)$matches[3];
        return $h * 3600 + $m * 60 + $s;
    }

    // Jeśli nie pasuje, zwróć 0 jako fallback
    return 0.0;
}

