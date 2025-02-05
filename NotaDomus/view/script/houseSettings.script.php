<?php
include_once('../../model/comment.class.php');
include_once('../../model/notification.class.php');
include_once('../../model/user.class.php');
include_once('../../model/house.class.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idComment = $_POST['idComment'];
    $action = $_POST['action'];

    if ($idComment != 0) {
        $comment = Comment::readComment($idComment);
    }
    // echap les caractere speciaux
    $content = htmlspecialchars(trim($_POST['content']));

    switch ($action) {
        case 'report':
            $ownerComment = $comment->getOwner();
            $reports = $comment->getReporting();
            $newReport = new Report(intval($_POST['idFlagger']), $_POST['categorie'], $_POST['content']);
            $canAddReport = true;
            foreach ($reports as $report) {
                if ($report == $newReport) {
                    $canAddReport = false;
                }
            }
            if ($canAddReport) {
                $comment->addReporting(intval($_POST['idFlagger']), $_POST['categorie'], $content);
                Notification::createNotification($ownerComment, $_POST['categorie'], $comment);
                echo "Le commentaire a été signalé";
            } else {
                echo "Le commentaire a déjà été signalé";
            }
            break;
        case 'modify':
            $comment->setContent($_POST['commentContent']);
            $comment->setRate($_POST['commentRate']);
            echo "Le commentaire a été modifié";
            break;
        case 'modifyHouse':
            $house = House::readHouse($_POST['idHouse']);
            $house->setPresentation($_POST['houseContent']);
            echo "La maison a été modifié";
            break;
        case 'delete':
            $comment->deleteComment();
            echo "Le commentaire a été supprimé";
            break;
        case 'addFriend':
            $ownerComment = $comment->getOwner();
            $newFriend = User::readUserById($_POST['idUser']);
            Notification::createNotification($ownerComment, 'ami', $newFriend);
            echo "La demande a été envoyée";
            break;
    }
}
?>