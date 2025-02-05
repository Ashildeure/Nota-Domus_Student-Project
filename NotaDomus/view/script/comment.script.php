<?php
include_once('../../model/comment.class.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comment = $_POST['commentText'];
    $rating = $_POST['starComment'];
    $user = $_POST['idUser'];
    $house = $_POST['idHouse'];

    // echap les caractere speciaux
    $comment = htmlspecialchars(trim($comment));

    // Enregistrement du commentaire
    Comment::createComment($comment, $rating, 0, 0, $user, $house);

    //echo "Votre commentaire a bien été enregistré";
}
?>