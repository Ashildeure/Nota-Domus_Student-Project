<?php
include_once __DIR__ . '/framework/view.fw.php';

global $databaseName;
$databaseName = 'notadomus';
// Initialisation a
error_reporting(E_ALL); // Error/Exception engine, always use E_ALL

ini_set('ignore_repeated_errors', true); // always use TRUE

ini_set('display_errors', true); // Error/Exception display, use FALSE only in production environment or real server. Use TRUE in development environment

ini_set('log_errors', true); // Error/Exception file logging engine.
ini_set('error_log', __DIR__ . '/log/errors.log'); // Logging file path

// Récupération de le controleur à activer
// Par défaut on lance l'action main
// ATTENTION : comme il y a des formulaires en GET et POST
//             il faut utiliser la variable super globale $_REQUEST
$ctrl = $_REQUEST['ctrl'] ?? 'main';

// Détruire la session en cours


// Liste des controleurs possibles
// Cette liste permet d'être sûr de ne pas charger de fichier inconnu
const CTRLS = ['main', 'userHouse', 'userMap', 'mod', 'logout', 'account'];

// Vérification que l'action est correcte
if (!in_array($ctrl, CTRLS)) {
    // Ouvre une page d'erreur
    $error = "Mauvais contrôleur :\"$ctrl\"";
    error_log('Un mauvais contrôleur a été choisi. Le script va afficher une erreur en front-end.');
    $view = new View();
    $view->assign('message', 'Cette page ne semble pas disponible. Veuillez vérifier l\'URL dans la barre d\'adresse.');
    $view->display('errorPage');
}
// Calcule le chemin vers le fichier du controleur
require_once "controller/$ctrl.ctrl.php";
// Charge le controleur
