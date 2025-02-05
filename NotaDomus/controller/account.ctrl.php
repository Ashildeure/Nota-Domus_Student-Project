<?php

include_once __DIR__ . '/../framework/view.fw.php';
include_once __DIR__ . '/../model/user.class.php';

if (session_status() === PHP_SESSION_NONE)
    session_start();

$login = $_SESSION['login'] ?? null;
// Création de la vue
$view = new View();

if ($login !== null) {
    $user = User::readUserByLogin($login);
} else {
    $user = null;
}

// Modification des informations
$input_mail = $_POST['input-mail'] ?? '';
$old_password = $_POST['old-password'] ?? '';
$new_password = $_POST['new-password'] ?? '';
$confirm_password = $_POST['confirm-password'] ?? '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (password_verify($old_password, $user->getPassword())) {
        if($input_mail != $user->getEmail() && Person::emailIsAvalaible($user, $input_mail)){
            $user->setEmail($input_mail);
        }
        if ($new_password == $confirm_password) {
            $user->setPassword($new_password);
        }
        $user->updateUser();
    }
}

// Passer les paramètres à la vue
$view->assign('user', $user);
// Charger la vue
$view->display('updateAccount');

