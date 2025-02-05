<?php
include_once('../../model/notification.class.php');
include_once('../../model/user.class.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $action = $_POST['action'];
    $idUser = $_POST['idUser']; // L'utilisateur à l'origine de la suppression ou la demande
    $user = User::readUserById($idUser);

    switch ($action) {
        case 'deleteFriend':
            $friend = User::readUserById($_POST['idFriend']);
            $user->removeFriend($friend);
            echo "L'ami a été supprimé";
            break;
        case 'addFriend':
            $newFriend = User::readUserByLogin($_POST['loginFriend']);
            Notification::createNotification($newFriend, 'ami', $user);
            echo "La demande a été envoyée";
            break;
    }
}
?>