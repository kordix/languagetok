<?php
require('db.php');

$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    // Rozpocznij transakcję (opcjonalnie)
    $dbh->beginTransaction();

    // 1. CREATE TEMPORARY TABLE
    $dbh->exec("CREATE TEMPORARY TABLE fragments_keep_ids (id INT PRIMARY KEY)");

    // 2. INSERT INTO TEMP TABLE
    $dbh->exec("
        INSERT INTO fragments_keep_ids (id)
        SELECT MIN(id)
        FROM fragments
        GROUP BY tekst
    ");

    // 3. DELETE FROM fragments
    $dbh->exec("
        DELETE FROM fragments
        WHERE id NOT IN (SELECT id FROM fragments_keep_ids)
    ");

    // 4. DROP TEMP TABLE
    $dbh->exec("DROP TEMPORARY TABLE fragments_keep_ids");

    // Commit (jeśli użyto transakcji)
    $dbh->commit();

} catch (PDOException $e) {
    // Rollback w razie błędu
    $dbh->rollBack();
    echo "Błąd: " . $e->getMessage();
}
