<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="public/design/styleFilter.css" rel="stylesheet">
    <link rel="icon" href="public/design/img/logo.png">
</head>

<body>

<input type="checkbox" id="toggle" checked hidden>
<div class="main-filter">

    <input type="checkbox" id="toggleFiltre" hidden>
    <!-- Logo filtre -->
    <label for="toggle" class="filtre-label" title="Filtrer">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#FFE" class="bi bi-funnel-fill"
             viewBox="0 0 16 16">
            <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5z"/>
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#FFE" class="bi bi-chevron-left" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0"/>
        </svg>
    </label>

    <!-- Frame entière -->
    <div class="frame-filtre">
        <div class="filtre-form">
            <label for="toggleFiltre" class="bouton-filtres">Filtrer par ⮂</label>
            <!-- Rectangle rouge en css -->
            <div class="rectangle-filter"></div>
            <!-- Formulaire des filtres -->
            <form action="?ctrl=userMap" class="filtres" method="POST">
                <div class="scrollZone">
                    <div class="social">
                        <div class="social-recom">
                            <label for="recommande">Recommandées par des amis</label>
                            <input type="checkbox" class="check" id="recommande"
                                   name="recommande" <?php
                            if (isset($filters) && isset($filters[0]) && $filters[0] == true) { ?> checked <?php
                            } ?>>
                        </div>
                        <div class="social-fav">
                            <label for="favoris">Favoris</label>
                            <input type="checkbox" class="check" id="favoris"
                                   name="favoris" <?php
                            if (isset($filters) && isset($filters[1]) && $filters[1] == true) { ?> checked <?php
                            } ?>>
                        </div>
                    </div>

<!--                    Séparateur-->
                    <hr>

                    <div class="era">
                        <legend>Époque de l'illustre :</legend>
                        <div class="range_container">
                            <div class="sliders_control">
                                <input id="fromSlider" type="range"
                                       value=<?php
                                       if (isset($filters) && isset($filters[2])){ ?> "<?= $filters[2][0] ?>" <?php
                                } else { ?> "14" <?php
                                } ?>
                                min="14" max="21" />
                                <input id="toSlider" type="range"
                                       value=<?php
                                       if (isset($filters) && isset($filters[2])){ ?> "<?= $filters[2][1] ?>" <?php
                            } else { ?> "21" <?php
                            } ?>
                                min="14" max="21" />
                            </div>
                            <div class="form_control">
                                <div class="form_control_container">
                                    <div class="form_control_container__time">Siècle min</div>
                                    <input class="form_control_container__time__input" type="number" id="fromInput"
                                           value=<?php
                                           if (isset($filters) && isset($filters[2])){ ?> "<?= $filters[2][0] ?>" <?php
                                    } else { ?> "14" <?php
                                    } ?>
                                    min="14" max="21" name="fromInput" />
                                </div>
                                <div class="form_control_container">
                                    <div class="form_control_container__time">Siècle max</div>
                                    <input class="form_control_container__time__input" type="number" id="toInput"
                                           value=<?php
                                           if (isset($filters) && isset($filters[2])){ ?> "<?= $filters[2][1] ?>" <?php
                                    } else { ?> "21" <?php
                                    } ?>
                                    min="14" max="21" name="toInput" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div>
                        <legend>Localisation :</legend>
                        <div class="loca">
                            <span>
                                <p>Régions :</p>
                                <button class="btnReset" type="button" id="btnResetReg">Remettre à zéro</button>
                            </span>
                            <div class="locaChoice">
                                <?php
                                $indiceRegion = 1;
                                foreach ($regions as $region) {
                                    ?>
                                    <span>
                                        <label for="reg<?= $indiceRegion ?>"><?= $region ?></label>
                                        <input type="checkbox" id="reg<?= $indiceRegion ?>" name="reg[]"
                                               value="<?= $region ?>" <?php
                                        if (isset($filters) && isset($filters[3]) && in_array($region, $filters[3])) { ?> checked <?php
                                        } ?>>
                                    </span>
                                    <?php
                                    $indiceRegion++;
                                }
                                ?>
                            </div>
                        </div>
                        <div class="loca">
                            <span>
                                <p>Départements :</p>
                                <button class="btnReset" type="button" id="btnResetDep">Remettre à zéro</button>
                            </span>
                            <div class="locaChoice">
                                <?php
                                $indiceDepartment = 1;
                                foreach ($departments as $department) {
                                    ?>
                                    <span>
                                    <label for="dep<?= $indiceDepartment ?>"><?= $department ?></label>
                                    <input type="checkbox" id="dep<?= $indiceDepartment ?>" name="dep[]"
                                           value="<?= $department ?>" <?php
                                    if (isset($filters) && isset($filters[4]) && in_array($department, $filters[4])) { ?> checked <?php
                                    } ?>>
                                </span>
                                    <?php
                                    $indiceDepartment++;
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div>
                        <legend>Note des maisons :</legend>
                        <div class="notes">
                            <div class="note">
                                <input type="checkbox" class="check" id="stars_1"
                                       name="stars_1" <?php
                                if (isset($filters) && isset($filters[5]) && isset($filters[5]['stars_1'])) { ?> checked <?php
                                } ?>>
                                <label for="stars_1">1★</label>
                            </div>
                            <div class="note">
                                <input type="checkbox" class="check" id="stars_2"
                                       name="stars_2" <?php
                                if (isset($filters) && isset($filters[5]) && isset($filters[5]['stars_2'])) { ?> checked <?php
                                } ?>>
                                <label for="stars_2">2★</label>
                            </div>
                            <div class="note">
                                <input type="checkbox" class="check" id="stars_3"
                                       name="stars_3" <?php
                                if (isset($filters) && isset($filters[5]) && isset($filters[5]['stars_3'])) { ?> checked <?php
                                } ?>>
                                <label for="stars_3">3★</label>
                            </div>
                            <div class="note">
                                <input type="checkbox" class="check" id="stars_4"
                                       name="stars_4" <?php
                                if (isset($filters) && isset($filters[5]) && isset($filters[5]['stars_4'])) { ?> checked <?php
                                } ?>>
                                <label for="stars_4">4★</label>
                            </div>
                            <div class="note">
                                <input type="checkbox" class="check" id="stars_5"
                                       name="stars_5" <?php
                                if (isset($filters) && isset($filters[5]) && isset($filters[5]['stars_5'])) { ?> checked <?php
                                } ?>>
                                <label for="stars_5">5★</label>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btnFilter">Filtrer</button>
            </form>
        </div>
        <div class="houses" id="houses">
            <!-- Affichage des maisons -->
            <!-- TODO :
                    - ATTENTION IL INCRÉMENTER LES FAVORIS DES MAISONS SINON ILS SONT TOUS CONNECTÉS
                    IL FAUT INCRÉMENTER L'ATTRIBUT ID DE L'INPUT FAVORIS ET L'ATTRIBUT FOR DU LABEL QUI SUIT
                    - ATTENTION IL FAUT INCRÉMENTER LES ÉTOILES DES MAISONS SINON C'EST JUSTE LA DERNIÈRE QUI VA PRENDRE LA NOTE
                    IL FAUT DONC MODIFIER LES ATTRIBUTS NAME DE CHAQUE INPUT
            -->
            <?php
            $indiceMaison = 1;
            if ($housesFilter == null) { ?>
                <p>Aucune maison ne correspond à la recherche</p>
            <?php }
            foreach ($housesFilter as $house) {
                ?>
                <!-- Début squelette maison -->
                <div class="house" house-id="<?= $house->getId() ?>">
                <span>
                    <form action="index.php?ctrl=userHouse" method="POST">
                        <input value="<?= $house->getId() ?>" name="idHouse" hidden>
                        <button class="titleHouse" type="submit"><?= $house->getName() ?></button>
                    </form>
                    <div>
                        <?php
                        if ($user !== null) {
                            if ($user->isInFavorite($house)) {
                                ?>
                                <input type="checkbox" name="favoriteFilter" id="<?= $indiceMaison ?>favorite"
                                       class="favorite"
                                       house-id="<?= $house->getId() ?>" user-id="<?= $user->getId() ?>"
                                       value="favorite"
                                       hidden checked>
                                <?php
                            } else {
                                ?>
                                <input type="checkbox" name="favoriteFilter" id="<?= $indiceMaison ?>favorite"
                                       class="favorite"
                                       house-id="<?= $house->getId() ?>" user-id="<?= $user->getId() ?>"
                                       value="favorite"
                                       hidden>
                                <?php
                            }
                            ?>
                            <label for="<?= $indiceMaison ?>favorite">
                                <svg class="heart" xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                     fill="gray" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                          d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314"/>
                                </svg>
                            </label>
                            <?php
                        } else { ?>
                            <input type="button" name="openPopupVisitor" id="openPopupVisitor" hidden>
                            <label for="openPopupVisitor">
                                <svg class="heart" xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                     fill="gray" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                          d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314"/>
                                </svg>
                            </label>
                        <?php } ?>
                        <input type="radio" name="markerFilter" id="<?= $indiceMaison ?>locate"
                               geo-id="<?= $house->getId() ?>" hidden>
                        <label for="<?= $indiceMaison ?>locate" house-id="<?= $house->getId() ?>" name="svgFilter">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#731702"
                                  viewBox="0 0 16 16" >
                                <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6"/>
                            </svg>
                        </label>
                    </div>
                </span>
                    <div class="ratingFilter">
                        <?php
                        for ($indiceEtoileFiltre = 5; $indiceEtoileFiltre >= 1; $indiceEtoileFiltre--) {
                            if ($indiceEtoileFiltre != (int)$house->getAvgRate()) {
                                ?>
                                <input type="radio" id="star<?= $indiceEtoileFiltre ?>"
                                       name="<?= $indiceMaison ?>starFilter" hidden disabled>
                                <label for="star<?= $indiceEtoileFiltre ?>">★</label>
                                <?php
                            } else {
                                ?>
                                <input type="radio" id="star<?= $indiceEtoileFiltre ?>"
                                       name="<?= $indiceMaison ?>starFilter" hidden disabled checked>
                                <label for="star<?= $indiceEtoileFiltre ?>">★</label>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <span>
                    <form action="?ctrl=userHouse" method="POST">
                        <input value="<?= $house->getId() ?>" name="idHouse" hidden>
                        <button class="titleHouse" type="submit">
                            <img src="https://maps.googleapis.com/maps/api/streetview?size=150x120&location=<?= $house->getLocation()->getLatitude() ?>,<?= $house->getLocation()->getLongitude() ?>&fov=80&heading=70&pitch=0&key=secret"
                                 alt="">
                        </button>
                    </form>
                    <div>
                        <p><?= $house->getLocation()->getAddresse() ?></p>
                        <p><?= $house->getLocation()->getRegionName() ?></p>
                        <p><?= $house->getLocation()->getDepartmentName() ?></p>
                        <?php if ($user !== null) { ?>
                            <button name="openPopupComment" data-id="<?= $house->getId() ?>">Commenter</button>
                        <?php } else { ?>
                            <button class="openPopupVisitor" name="openPopupVisitor">Commenter</button>
                        <?php } ?>
                    </div>
                </span>
                    <!-- Fin squelette maison -->
                </div>
                <?php
                $indiceMaison++;
            }
            ?>
        </div>
    </div>
</div>
<!-- Formulaire pour poster un commentaire -->
<div class="popupComment" id="myPopupComment">
    <form class="popupContentComment" id="popupContentComment" action="">
        <h2>Proposez votre commentaire</h2>
        <span>
            <p>Note* : </p>
            <div class="ratingComment">
                <input type="radio" id="starComment5" name="starComment" value="5" hidden>
                <label for="starComment5">★</label>
                <input type="radio" id="starComment4" name="starComment" value="4" hidden>
                <label for="starComment4">★</label>
                <input type="radio" id="starComment3" name="starComment" value="3" hidden>
                <label for="starComment3">★</label>
                <input type="radio" id="starComment2" name="starComment" value="2" hidden>
                <label for="starComment2">★</label>
                <input type="radio" id="starComment1" name="starComment" value="1" hidden>
                <label for="starComment1">★</label>
            </div>
        </span>
        <p class="verifContentComment" id="verifContentRateComment">Veuillez renseigner une note</p>
        <p>Commentaire* :</p>
        <p class="verifContentComment" id="verifContentTextComment">Veuillez renseigner un commentaire</p>
        <textarea rows="6" id="commentText"></textarea>
        <!-- Mettre l'id du user -->
        <input id="idUser" value="<?= $user === null ? 0 : $user->getId() ?>" hidden>
        <span>
            <button id="closePopupComment">Annuler</button>
            <button id="changeContentComment" type="button">Envoyer</button>
        </span>
    </form>
    <div class="popupContentComment2" id="popupContentComment2">
        <p>Votre commentaire a bien été enregistré</p>
        <button id="closePopupComment2" type="button">OK</button>
    </div>
</div>
<!-- Pop-up Visiteur -->
<div class="popupVisitor" id="popupVisitor">
    <div class="popupContentVisitor">
        <h2>Vous n'êtes pas connecté</h2>
        <p>Cette fonctionalité n'est pas disponible si vous n'avez pas de compte.</p>
        <span>
            <button class="closePopupVisitor" id="closePopupVisitor" type="button">Ignorer</button>
            <form action="?ctrl=main">
                <button type="submit" class="connectionPopupVisitor">Connexion</button>
            </form>
        </span>
    </div>
</div>
<!-- Script pour les filtres -->
<script src="view/script/filter.script.js"></script>
<!-- Script pour le pop-up de commentaire -->
<script src="view/script/comment.script.js"></script>
<!-- Script pour les visiteurs -->
<script src="view/script/visitor.script.js"></script>
</body>

</html>