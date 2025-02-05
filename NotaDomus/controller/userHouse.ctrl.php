<?php

require_once __DIR__ . '/../framework/view.fw.php';
require_once __DIR__ . '/../model/house.class.php';
require_once __DIR__ . '/../model/comment.class.php';
require_once __DIR__ . '/../model/user.class.php';
require_once __DIR__ . '/../model/notification.class.php';
require_once __DIR__ . '/../model/ohAdmin.class.php';

session_start();
$login = $_SESSION['login'] ?? null;

$idHouse = $_POST['idHouse'] ?? 1;

$house = null;
try {
    $house = House::readHouse($idHouse);
} catch (RuntimeException $e) {
    $view = new View();
    $view->assign('message', 'Il semble que cette maison n\'existe plus.');
    $view->display('errorPage');
}

if ($login !== null) {
    $user = User::readUserByLogin($login);
    if ($user->getRole() == "ohAdmin") {
        $ohAdmin = OhAdmin::readOhAdmin($user->getId());
    } else {
        $ohAdmin = null;
    }
    $notifications = $user->getNotifications();
    $friends = $user->getFriends();
} else {
    $user = null;
    $notifications = null;
    $friends = null;
    $ohAdmin = null;
}

$view = new View();

$view->assign('user', $user);
$view->assign('house', $house);
$view->assign('notifications', $notifications);
$view->assign('friends', $friends);
$view->assign('ohAdmin', $ohAdmin);

$view->display('userHouse');
