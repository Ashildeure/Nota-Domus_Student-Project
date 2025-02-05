<?php
global $databaseName;
$databaseName = 'notadomus_test';
require_once __DIR__ . '/../../model/DAO.class.php';
require_once __DIR__ . '/../../model/user.class.php';
$dao = DAO::get();
$user = new User('flavi', 'flaiv', 'flavi@flav.com', 0);
User::createUser($user);