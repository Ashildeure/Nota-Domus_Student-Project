<?php
include_once('../../model/notification.class.php');
include_once('../../model/user.class.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idNotif = $_POST['idNotif'];
    $newFriend = $_POST['newFriend'];
    $notif = Notification::readNotification($idNotif);
    if ($notif->getData()::class == "User" && $newFriend) {
        $notif->getOwner()->addFriend($notif->getData());
        echo "L'ami a été ajouté";
    }
    $notif->deleteNotification();

    echo "La notification a bien été supprimée";
}