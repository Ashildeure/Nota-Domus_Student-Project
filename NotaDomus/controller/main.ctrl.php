<?php

include_once __DIR__ . '/../framework/view.fw.php';
include_once __DIR__ . '/../model/user.class.php';

if (session_status() === PHP_SESSION_NONE)
    session_start();
session_destroy();

// Récupération des données du formulaire
$btn = $_POST['action'] ?? ''; // Bouton cliqué
$login = htmlspecialchars(trim($_POST['login'] ?? '')); // Nom d'utilisateur
$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL); // Validation de l'email
$password = htmlspecialchars(trim($_POST['password'] ?? ''));

// Création de la vue
$view = new View();

switch ($btn) {
    case 'inscrire':
        // Validation des champs
        if (empty($login) || empty($email) || empty($password)) {
            $message = 'Tous les champs de ce formulaire sont requis.';
            // J'utilise une variable intermédiaire pour éviter que jetbrains reporte que la variable est indéfinie dans errorPage
            $view->assign('message', $message);
            $view->display('errorPage');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = 'L\'adresse email renseignée est invalide';
            // J'utilise une variable intermédiaire pour éviter que jetbrains reporte que la variable est indéfinie dans errorPage
            $view->assign('message', $message);
            $view->display('errorPage');
        }

        // Créer un nouvel utilisateur seulement si celui-ci n'existe pas déjà
        $newUser = new User(1, $login, $password, $email, 0);  // Crée l'utilisateur (active = 0 par défaut)
        if (!User::userExists($newUser->getEmail())) {
            // Si l'utilisateur n'existe pas, on l'insère dans la base de données
            User::createUser($password, $email, $login);

            // Maintenant, il faut stocker l'email et le login dans la session pour pouvoir le récupérer une autre fois
            session_start();
            $_SESSION['login'] = $login;
            $_SESSION['email'] = $email;

            // Redirection vers la page utilisateur
            header('Location: index.php?ctrl=userMap'); // Assurez-vous que `userMap.php` est bien le bon lien
            // TODO: valider qu'on peut utiliser cette ligne à la place, sinon supprimer ce message :
            // require_once __DIR__ . '/userMap.ctrl.php';
            exit();
        } else {
            // Si l'utilisateur existe déjà, on montre une page d'erreur
            $message = 'Ce nom est déjà utilisé. Veuillez en choisir un autre.';
            // J'utilise une variable intermédiaire pour éviter que jetbrains reporte que la variable est indéfinie dans errorPage
            $view->assign('message', $message);
            $view->display('errorPage');
        }

    case 'connecter':
        // Validation de l'email et mot de passe
        if (empty($login) || empty($password)) {
            // Si au moins l'un des deux est vide alors qu'on vient de se connecter, on revient sur la page de connexion
            $view->display('main');
        }

        // Récupérer l'utilisateur depuis la base pour vérifier
        try {
            $newUser = User::readUserByLogin($login);
        } catch (RuntimeException) {
            $view->display('main');
        }

        // Dernière vérification du mot de passe
        if (password_verify($password, $newUser->getPassword())) {
            // Si on est ici, c'est que le mot de passe est correct et que l'utilisateur existe
            // Maintenant, créons la session et enregistrons les infos dedans

            session_start();
            $_SESSION['login'] = $login;
            $_SESSION['email'] = $email;

            // Redirection vers la page utilisateur
            header('Location: index.php?ctrl=userMap'); // Assurez-vous que `userMap.php` est bien le bon lien
            // TODO: valider qu'on peut utiliser cette ligne à la place, sinon supprimer ce message :
            // require_once __DIR__ . '/userMap.ctrl.php';
            exit();
        } else {
            // Si l'utilisateur n'existe pas ou le mot de passe est incorrect
            $view->display('main');
        }

    case 'sansconnecter':
        // L'utilisateur veut continuer sans se connecter
        header('Location: index.php?ctrl=userMap'); // Assurez-vous que `userMap.php` est bien le bon lien
        // TODO: valider qu'on peut utiliser cette ligne à la place, sinon supprimer ce message :
        // require_once __DIR__ . '/userMap.ctrl.php';
        exit();

    default:
        // Par défaut, il faut regarder si une session existe avec un login
        session_start();
        if (isset($_SESSION['login'])) {
            // Si la variable existe c'est que l'utilisateur s'est déjà connecté, on va donc plutôt chercher userMap
            header('Location: index.php?ctrl=userMap'); // Assurez-vous que `userMap.php` est bien le bon lien
        }
        // Sinon on passe par l'inscription ou la connexion
        $view->display('main');
}