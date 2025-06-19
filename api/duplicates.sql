    CREATE TEMPORARY TABLE fragments_keep_ids (
        id INT PRIMARY KEY
    );

    INSERT INTO fragments_keep_ids (id)
    SELECT MIN(id)
    FROM fragments
    GROUP BY tekst;

    DELETE FROM fragments
    WHERE id NOT IN (SELECT id FROM fragments_keep_ids);

    DROP TEMPORARY TABLE fragments_keep_ids;
