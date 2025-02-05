<?php

global $databaseName;
$databaseName = 'notadomus_test';

require_once __DIR__ . '/../../model/DAO.class.php';

$dao = DAO::get();
$dao->quickExecute('BEGIN');
$dao->quickExecute('
    INSERT INTO Comment VALUES (1, 198, 1, \'je sais pas\', 4.0, 0, 1, 30)
');
$dao->quickExecute('ROLLBACK');
$dao->quickExecute('
    INSERT INTO Comment VALUES (2, 198, 1, \'je sais pas épisode 2\', 4.0, 0, 1, 30)
');
$dao->quickExecute('COMMIT');