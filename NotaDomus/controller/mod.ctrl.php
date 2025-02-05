<?php
include_once __DIR__ . '/../framework/view.fw.php';
include_once __DIR__ . '/../model/comment.class.php';
include_once __DIR__ . '/../model/moderator.class.php';

$login = $_SESSION['login'];
$user = User::readUserByLogin($login);

//----Recuperation des Action sur les bouton
$reportPanelOpen = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') { // verification que le formulaire a été envoyé
    if (isset($_POST['commentToBeDeleted'])) {
        $comment = Comment::readComment($_POST['commentToBeDeleted']);
        $comment->deleteComment();
    }
    if (isset($_POST['commentToBeReport'])) {
        $comment = Comment::readComment($_POST['commentToBeReport']);
        $comment->addReporting();
    }
    $reportPanelOpen = true;
}

//--Opertion-sur le modele
$moderateur = Moderator::readModerator($user->getId());
$reportedComments = Moderator::getReportedComments();
$comments = Comment::readAllComments();

//----Construction de la vue
$view = new View();

// Passer les paramètres à la vue
$view->assign('reportedComments', $reportedComments);
$view->assign('comments', $comments);
$view->assign('reportPanelOpen', $reportPanelOpen);
$view->assign('user', $user);

// Charger la vue
$view->display("mod");



