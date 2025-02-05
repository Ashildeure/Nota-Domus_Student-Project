<?php
//--- gestion pour l'affichage sans warning
error_reporting(E_ALL & ~E_WARNING); // Affiche tout sauf les warnings
ini_set('display_errors', 1);

//----Import
require_once __DIR__ . '/../../model/comment.class.php';

//--recuperation ds info de session
session_start();
if(!isset($_SESSION["login"])){
    $user = User::readUserById(100);
}else{
    $user = User::readUserByLogin($_SESSION["login"]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $search = $_POST['search'] ?? '';
    $userSugests = $user->readLike($search);
    // Génération du HTML des suggestions
    if (!empty($userSugests)) {
        foreach ($userSugests as $userSugest) {
            echo '<button class="login-friend-Suggestion">' . htmlspecialchars($userSugest->getLogin()) . '</button>';
        }
    } else {
        echo '<p>Aucune suggestion trouvée.</p>';
    }
} else {
    echo '<p>Méthode non autorisée.</p>';
}

