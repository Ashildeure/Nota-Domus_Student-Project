<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="public/design/styleNavBar.css" rel="stylesheet">
    <title>NotaDomus</title>
    <link rel="icon" href="public/design/img/logo.png">
</head>
<body>
<!-- Barre de navigation -->
<nav class="navBar">
    <ul>
        <li class="leftSide">
            <!-- Logo -->
            <a class="logo" href=""><img src="public/design/img/logo.png" alt="logo"></a>
            <!-- Bouton maison propriétaire -->
            <?php
             if ($ohAdmin != null) { ?>
                <form action="?ctrl=userHouse" method="POST">
                    <input name="idHouse" value="<?= $ohAdmin->getHouse()->getId() ?>" class="inputHouse" hidden>
                    <button class="maMaison" type="submit">Ma maison d'illustre</button>
                </form>
                <?php
            } ?>
        </li>
        <li>
            <!-- Loupe + Barre de recherche -->
            <form action="?ctrl=userMap" class="search" method="POST">
                <button class="btnLoupe">
                    <img src="public/design/img/chercher.png" class="loupe" alt="loupe">
                </button>
                <input type="text" class="champ" name="champ" id="search-house"
                    <?php
                    if (isset($filters) && isset($filters[0])) { ?> value="<?= $filters[0] ?>" <?php
                    } else { ?> placeholder="Entrez le nom d'un illustre, d'une oeuvre ou d'un lieu" <?php
                    } ?>>
                <div id="house-Suggestion">
                <!--  est remplit par scriptSearchHouse.php-->
                </div>
            </form>
        </li>
        <li>
            <!-- Bouton notifications -->
            <?php if ($user !== null) { ?>
            <button name="openPopupNotif" class="cloche">
                <?php } else { ?>
                <button name="openPopupVisitor" class="cloche">
                    <?php } ?>
                    <?php
                    if (!empty($notifications)) { ?>
                        <svg version="1.1" width="36" height="36" fill="#731702" viewBox="0 0 36 36"
                             preserveAspectRatio="xMidYMid meet" xmlns="http://www.w3.org/2000/svg"
                        >
                            <title>notification-solid-badged</title>
                            <path class="clr-i-solid--badged clr-i-solid-path-1--badged"
                                  d="M18,34.28A2.67,2.67,0,0,0,20.58,32H15.32A2.67,2.67,0,0,0,18,34.28Z"></path>
                            <path class="clr-i-solid--badged clr-i-solid-path-2--badged"
                                  d="M32.85,28.13l-.34-.3A14.37,14.37,0,0,1,30,24.9a12.63,12.63,0,0,1-1.35-4.81V15.15a10.92,10.92,0,0,0-.16-1.79A7.5,7.5,0,0,1,22.5,6c0-.21,0-.42,0-.63a10.57,10.57,0,0,0-3.32-1V3.11a1.33,1.33,0,1,0-2.67,0V4.42A10.81,10.81,0,0,0,7.21,15.15v4.94A12.63,12.63,0,0,1,5.86,24.9a14.4,14.4,0,0,1-2.47,2.93l-.34.3v2.82H32.85Z"></path>
                            <circle class="clr-i-solid--badged clr-i-solid-path-3--badged clr-i-badge" cx="30" cy="6"
                                    r="5"></circle>
                            <rect x="0" y="0" width="36" height="36" fill-opacity="0"/>
                        </svg>
                        <?php
                    } else { ?>
                        <svg version="1.1" width="36" height="36" fill="#731702" viewBox="0 0 36 36"
                             preserveAspectRatio="xMidYMid meet" xmlns="http://www.w3.org/2000/svg"
                        >
                            <title>notification-solid</title>
                            <path class="clr-i-solid clr-i-solid-path-1"
                                  d="M32.85,28.13l-.34-.3A14.37,14.37,0,0,1,30,24.9a12.63,12.63,0,0,1-1.35-4.81V15.15A10.81,10.81,0,0,0,19.21,4.4V3.11a1.33,1.33,0,1,0-2.67,0V4.42A10.81,10.81,0,0,0,7.21,15.15v4.94A12.63,12.63,0,0,1,5.86,24.9a14.4,14.4,0,0,1-2.47,2.93l-.34.3v2.82H32.85Z"></path>
                            <path class="clr-i-solid clr-i-solid-path-2" d="M15.32,32a2.65,2.65,0,0,0,5.25,0Z"></path>
                            <rect x="0" y="0" width="36" height="36" fill-opacity="0"/>
                        </svg>
                        <?php
                    } ?>
                </button>
        </li>
    </ul>

</nav>
<div class="popupNotif" id="myPopupNotif">
    <div class="popup-content">
        <?php
        if ($notifications != null) {
            foreach ($notifications as $notification) {
                if ($notification->getData()::class == "User") { //si data est un object User ?>
                    <!-- Nouvel Ami -->
                    <div class="notif" name="notif" notif-id="<?= $notification->getId() ?>">
                        <span>
                            <h2>Nouvel Ami !</h2>
                            <input name="closeNotif" id="closeNotif" notif-id="<?= $notification->getId() ?>" hidden>
                            <label for="closeNotif">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                     class="bi bi-x-lg" viewBox="0 0 16 16">
                                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                                </svg>
                            </label>
                        </span>
                        <p><?= $notification->getData()->getLogin() ?> vous a demandé en ami</p>
                        <span>
                            <input type="button" name="closeNotif" notif-id="<?= $notification->getId() ?>"
                                   value="Refuser">
                            <input type="button" name="addFriend" notif-id="<?= $notification->getId() ?>"
                                   value="Accepter">
                        </span>
                        <hr>
                    </div>
                    <?php
                } else if ($notification->getData()::class == "Comment") { ?>
                    <!-- Signalement -->
                    <div class="notif" name="notif" notif-id="<?= $notification->getId() ?>">
                        <span>
                            <h2>Signalement !</h2>
                            <input name="closeNotif" id="closeNotif" notif-id="<?= $notification->getId() ?>" hidden>
                            <label for="closeNotif">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                     class="bi bi-x-lg" viewBox="0 0 16 16">
                                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                                </svg>
                            </label>
                        </span>
                        <p>Votre commentaire sur <?= $notification->getData()->getHouse()->getName() ?> a été signalé
                            par un autre utilisateur</p>
                        <form action="?ctrl=userHouse" method="POST">
                            <input name="idHouse" value="<?= $notification->getData()->getHouse()->getId() ?>" hidden>
                            <button type="submit">Voir la maison</button>
                        </form>
                        <hr>
                    </div>
                    <?php
                } else { ?>
                    <!-- Recommandation -->
                    <div class="notif" name="notif" notif-id="<?= $notification->getId() ?>">
                        <span>
                            <h2>Recommandation !</h2>
                            <input name="closeNotif" id="closeNotif" notif-id="<?= $notification->getId() ?>" hidden>
                            <label for="closeNotif">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                     class="bi bi-x-lg" viewBox="0 0 16 16">
                                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                                </svg>
                            </label>
                        </span>
                        <p>pseudo vous recommande d'aller visiter <?= $notification->getData()->getName() ?></p>
                        <form action="?ctrl=userHouse" method="POST">
                            <input name="idHouse" value="<?= $notification->getData()->getId() ?>" hidden>
                            <button type="submit">Consulter</button>
                        </form>
                        <hr>
                    </div>
                    <?php
                } ?>
                <?php
            }
        } ?>
    </div>
</div>
<!-- Script Pop-up notification -->
<script src="view/script/notif.script.js"></script>
<!-- Script recherche de maison -->
<script src="view/script/houseSearch.script.js"></script>
</body>
</html>