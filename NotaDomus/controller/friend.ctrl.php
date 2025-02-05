<?php

include_once __DIR__ . '/../framework/view.fw.php';
include_once __DIR__ . '/../model/user.class.php';
include_once __DIR__ . '/../model/notification.class.php';

$login = $_SESSION['login'] ?? null;

$btnfriend = $_POST['btnfriend'] ?? null;
//var_dump($btnfriend);
//echo $btnfriend;
$nameFriend = $_POST['nameFriend'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //echo 'Formulaire soumis.';
    //var_dump($_POST);
} else {
    // echo 'Formulaire non soumis.';
}

// Construction de la vue
$view = new View();

if ($login !== null) {
    $user = User::readUserByLogin($login);

    // Comptage du nombre d'amis
    $friends = $user->getFriends();
    $nb = count($friends);

    // Affichage des commentaires

    $comments = [];

    foreach ($friends as $friend) {
        $friendComments = $friend->getComments();
        foreach ($friendComments as $comment)
            $comments[] = $comment;
    }

    $myComments = $user->getComments();
} else {
    $user = null;
    $friends = null;
    $nb = 0;
    $comments = null;
    $myComments = null;
}


//-----Fin Partie Amis-----

// Passer les paramètres à la vue
$view->assign('user', $user);
$view->assign('friends', $friends);
$view->assign('nb', $nb);
$view->assign('comments', $comments);
$view->assign('myComments', $myComments);

// Charger la vue
$view->display('friends');