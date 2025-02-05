<?php
include_once('../../model/comment.class.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idComment = $_POST['idComment'];
    $thumbUp = $_POST['thumbUp'];
    $comment = Comment::readComment($idComment);
    if ($thumbUp) {
        $comment->addLike();
        echo "+1";
    } else {
        $comment->addDislike();
        echo "-1";
    }
    echo "Le like a été pris en compte";
}