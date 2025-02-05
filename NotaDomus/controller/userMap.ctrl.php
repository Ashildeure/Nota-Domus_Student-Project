<?php
// Ce contrôleur gère la vue de la carte et celles associées (filtres et amis)
require_once __DIR__ . '/../framework/view.fw.php';
require_once __DIR__ . '/../model/house.class.php';
require_once __DIR__ . '/../model/location.class.php';
require_once __DIR__ . '/../model/user.class.php';
require_once __DIR__ . '/../model/notification.class.php';
require_once __DIR__ . '/../model/ohAdmin.class.php';


if (session_status() === PHP_SESSION_NONE)
    session_start();

$login = $_SESSION['login'] ?? null;
$email = $_SESSION['email'] ?? null;

//-----Partie Filtre-----
// Récupération des données
if ($login !== null) {
    $user = User::readUserByLogin($login);
    if ($user->getRole() == "ohAdmin") {
        $ohAdmin = OhAdmin::readOhAdmin($user->getId());
    } else {
        $ohAdmin = null;
    }
    $notifications = $user->getNotifications();
} else {
    $user = null;
    $notifications = null;
    $ohAdmin = null;
}
//var_dump($login); // TODO: à supprimer à terme (pas forcément maintenant)
$filters = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') { // verification que le formulaire a été envoyé
    if (isset($_POST['champ'])) {
        $champ = $_POST['champ'];
        $housesFiltered = House::readLike($champ);
        $filters[] = $_POST['champ'];
    } else {
        // TODO: Garder les infos du formulaire
        // Récupération des informations des cases à cocher
        $recommande = isset($_POST['recommande']);
        $favoris = isset($_POST['favoris']);

        // Récupération des plages d'époques
        $fromCentury = $_POST['fromInput'] ?? 14;
        $toCentury = $_POST['toInput'] ?? 21;
        $epoque = [$fromCentury, $toCentury];

        // Récupération de la localisation
        $regions = $_POST['reg'] ?? [];
        $departements = $_POST['dep'] ?? [];

        // Récupération des notes sélectionnées
        $selectedNotes = [];
        for ($i = 1; $i <= 5; $i++) {
            if (isset($_POST['stars_' . $i])) {
                $selectedNotes['stars_' . $i] = $i;
            }
        }
        $housesFiltered = $login === null
            ? House::readFilters($epoque, $regions, $departements, $selectedNotes, false, false, 0)
            : House::readFilters($epoque, $regions, $departements, $selectedNotes, $favoris, false, $user->getId());
        $filters = [$recommande, $favoris, $epoque, $regions, $departements, $selectedNotes];
    }
} else {
    $housesFiltered = House::readFilters([], [], [], [], false, false, 0);
}

//Calcul
$regionsChoice = Location::readRegions();
$departementsChoice = Location::readDepartments();


//-----Fin Partie Filtre-----


// Construction de la vue
$view = new View();

// Passer les paramètres à la vue
$view->assign('filters', $filters);
$view->assign('housesFilter', $housesFiltered);
$view->assign('user', $user);
$view->assign('regions', $regionsChoice);
$view->assign('departments', $departementsChoice);
$view->assign('notifications', $notifications);
$view->assign('ohAdmin', $ohAdmin);

// Charger la vue
$view->display('userMap');
