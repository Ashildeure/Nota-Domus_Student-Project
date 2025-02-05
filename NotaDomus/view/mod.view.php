<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/public/design/styleMod.css" rel="stylesheet">
    <link rel="icon" href="../public/design/img/logo.png">
</head>

<body>
<input type="checkbox" id="mod_toggle" hidden<?php if ($reportPanelOpen): ?> checked <?php endif; ?> >
<div class="mod_main">


    <!-- Logo Moderation -->
    <label for="mod_toggle" class="mod-label">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#F4DFCA" class="bi bi-megaphone-fill"
             viewBox="0 0 16 16">
            <path d="M13 2.5a1.5 1.5 0 0 1 3 0v11a1.5 1.5 0 0 1-3 0zm-1 .724c-2.067.95-4.539 1.481-7 1.656v6.237a25 25 0 0 1 1.088.085c2.053.204 4.038.668 5.912 1.56zm-8 7.841V4.934c-.68.027-1.399.043-2.008.053A2.02 2.02 0 0 0 0 7v2c0 1.106.896 1.996 1.994 2.009l.496.008a64 64 0 0 1 1.51.048m1.39 1.081q.428.032.85.078l.253 1.69a1 1 0 0 1-.983 1.187h-.548a1 1 0 0 1-.916-.599l-1.314-2.48a66 66 0 0 1 1.692.064q.491.026.966.06"/>
        </svg>
    </label>

    <!-- Frame entière -->
    <div class="frame-mod">
        <!-- Rectangle rouge en css -->
        <div class="rectangle"></div>
        <div class="mod-form">
            <div class="mod-profil">
                <div class="mod-myProfil">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                         class="bi bi-person-circle" viewBox="0 0 16 16">
                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                        <path fill-rule="evenodd"
                              d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                    </svg>
                    <p name="mod-loginUser" class="mod-loginUser"><?= $user === null ? 0 : $user->getLogin() ?></p>
                    <a href="?ctrl=account" class="mod-modif">Modifier le compte</a>
                </div>
                <div class="mod-logOut">
                    <a href="index.php?ctrl=main" class="mod-btnlogout">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                             class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                  d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                            <path fill-rule="evenodd"
                                  d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="type_sign">
                <button class="signal-button" id="signal-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#F4DFCA"
                         class="bi bi-megaphone-fill" viewBox="0 0 16 16">
                        <path d="M13 2.5a1.5 1.5 0 0 1 3 0v11a1.5 1.5 0 0 1-3 0zm-1 .724c-2.067.95-4.539 1.481-7 1.656v6.237a25 25 0 0 1 1.088.085c2.053.204 4.038.668 5.912 1.56zm-8 7.841V4.934c-.68.027-1.399.043-2.008.053A2.02 2.02 0 0 0 0 7v2c0 1.106.896 1.996 1.994 2.009l.496.008a64 64 0 0 1 1.51.048m1.39 1.081q.428.032.85.078l.253 1.69a1 1 0 0 1-.983 1.187h-.548a1 1 0 0 1-.916-.599l-1.314-2.48a66 66 0 0 1 1.692.064q.491.026.966.06"/>
                    </svg>
                    Signalements
                </button>
                <button class="recent-button" id="recent-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#F4DFCA"
                         class="bi bi-megaphone-fill" viewBox="0 0 16 16">
                        <path d="M13 2.5a1.5 1.5 0 0 1 3 0v11a1.5 1.5 0 0 1-3 0zm-1 .724c-2.067.95-4.539 1.481-7 1.656v6.237a25 25 0 0 1 1.088.085c2.053.204 4.038.668 5.912 1.56zm-8 7.841V4.934c-.68.027-1.399.043-2.008.053A2.02 2.02 0 0 0 0 7v2c0 1.106.896 1.996 1.994 2.009l.496.008a64 64 0 0 1 1.51.048m1.39 1.081q.428.032.85.078l.253 1.69a1 1 0 0 1-.983 1.187h-.548a1 1 0 0 1-.916-.599l-1.314-2.48a66 66 0 0 1 1.692.064q.491.026.966.06"/>
                    </svg>
                    Récents
                </button>
            </div>

            <div class="frame-coms-mod">
                <!-- Commentaires signalés -->
                <div class="coms-signal" id="coms-signal">
                    <?php
                    foreach ($reportedComments as $comment): ?>
                        <!-- Squelette du commentaire -->
                        <article class="comment-signal">
                        <span class="com-part1-signal">
                            <p><?= $comment->getOwner()->getLogin() ?> <?= $comment->getCreationDate()?></p>
                            <div class="ligne1_signal">
                                <span class="tiny_com_rating_mod">
                                    <?= $comment->getHouse()->getStars() ?>
                                </span>
                                <button class="dots-button" name="dots-button">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="16" fill="#731702"
                                         class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                        <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                                    </svg>
                                </button>
                                <div class="option-signal" name="option-signal">
                                    <button class="remove-button" id="remove-button"
                                            data-value="<?= $comment->getId() ?>">Supprimer</button>
                                    <button class="see-button" id="see-button"
                                            data-value="<?= $comment->getHouse()->getId() ?>">Voir</button>
                                </div>
                            </div>
                        </span>
                            <div class="ligne2_signal">
                                <p class="house-name"><?= $comment->getHouse()->getName() ?></p>

                            </div>
                            <p class="text-comment"><?= $comment->getContent() ?> </p>
                            <div class="popup-signCause">
                                <p class="title-signCause"><?= $comment->getReporting()[0]->getCategorie() ?></p>
                                <p class="signCause"><?= $comment->getReporting()[0]->getContent() ?></p>
                                <p class="signaler"><?= $comment->getReporting()[0]->getFlagger()->getlogin() ?></p>
                            </div>
                        </article>
                        <!-- Fin squelette -->
                    <?php endforeach; ?>
                </div>

                <!-- Commentaires récents -->
                <div class="coms-recent" id="coms-recent">
                    <?php
                    foreach ($comments as $comment): ?>
                        <!-- Squelette du commentaire -->
                        <article class="comment-recent">
                            <span class="com-part1-recent">
                                <p><?= $comment->getOwner()->getLogin() ?> <?= $comment->getCreationDate()?></p>
                                <div class="ligne1_recent">
                                    <span class="tiny_com_rating_mod">
                                        <?= $comment->getHouse()->getStars() ?>
                                    </span>
                                    <button class="dots-button" name="dots-button">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="16" fill="#731702"
                                             class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                            <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                                        </svg>
                                    </button>
                                    <div class="option-recent" name="option-recent">
                                        <button class="remove-button" id="remove-button"
                                                data-value="<?= $comment->getId() ?>">Supprimer</button>
                                        <!--pour l'instant pas uttiliser                                       <button class="report-button" id="report-button" data-value="-->
                                        <?php //= $comment->getId()?><!--" data-value2="-->
                                        <?php //= $c->getId()?><!--"">Signaler</button>-->
                                        <button class="see-button" id="see-button"
                                                data-value="<?= $comment->getHouse()->getId() ?>">Voir</button>
                                    </div>
                                </div>
                            </span>
                            <p> <?= $comment->getHouse()->getName() ?></p>
                            <p><?= $comment->getContent() ?> </p>
                        </article>
                        <!-- Fin squelette -->
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
<!-- Pop-up -->
<!-- Supprimer -->
<div class="popup-removeMod" id="popup-removeMod">
    <div class="popup-content-removeMod" id="popup-content-removeMod">
        <p>Le commentaire à bien été supprimé</p>
        <button class="closePopup-removeMod" id="closePopup-removeMod" type="button">OK</button>
    </div>
</div>
<!-- Signaler -->
<div class="popup-signalMod" id="popup-signalMod">
    <div class="popup-content-signalMod" id="popup-content-signalMod">
        <form action="">
            <h2>Signalement</h2>
            <select id="categorie_signalement" name="categorie">
                <option value="option1">Option 1</option>
                <option value="option2">Option 2</option>
                <option value="option3">Option 3</option>
                <option value="option4">Option 4</option>
            </select>
            <!-- <p class="empty_option">Cette option doit être renseignée</p> -->

            <label for="explication_report">Explication :</label>
            <textarea id="explication_report" name="explication_report" rows="6" cols="20"></textarea>

            <div id="nav_report">
                <span class="closePopup-signalMod" id="closePopup-signalMod">Annuler</span>
                <span class="changeContent-signalMod" id="changeContent-signalMod">Envoyer</span>
            </div>
        </form>
    </div>
</div>
<div class="popup-thx-signalMod" id="popup-thx-signalMod">
    <div class="popup-content-thx-signalMod" id="popup-content-thx-signalMod">
        <p>Merci pour votre signalement, un modérateur le prendra en charge au plus vite</p>
        <button id="closePopup-thx-signalMod" type="button">OK</button>
    </div>
</div>
<!-- Script pour la modération -->
<script src="view/script/mod.script.js"></script>

</body>

</html>