<!DOCTYPE html>
<html lang="fr" xmlns="http://www.w3.org/1999/html">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../public/design/styleFriends.css" rel="stylesheet">
    <link rel="icon" href="../public/design/img/logo.png">
</head>

<body>
<?php if ($user !== null) { ?>
    <input type="checkbox" id="toggle-friends" hidden>
<?php } else { ?>
    <input type="button" id="toggle-friends" name="openPopupVisitor" hidden>
<?php } ?>
<div class="main">

    <!-- Logo amis -->
    <?php if ($user !== null) { ?>
        <label for="toggle-friends" class="friends-label" title="Amis">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#F4DFCA" class="bi bi-person-fill"
                 viewBox="0 0 16 16">
                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#F4DFCA" class="bi bi-chevron-right" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708"/>
            </svg>
        </label>
    <?php } else { ?>
        <label for="openPopupVisitor" class="friends-label" title="Amis">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#F4DFCA" class="bi bi-person-fill"
                 viewBox="0 0 16 16">
                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
            </svg>
        </label>
    <?php } ?>
    <!-- Frame entière -->
    <div class="frame-friends">
        <!-- Rectangle rouge en css -->
        <div class="rectangle"></div>
        <div class="friends-form">

            <div class="profil">
                <div class="myProfil">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                         class="bi bi-person-circle" viewBox="0 0 16 16">
                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                        <path fill-rule="evenodd"
                              d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                    </svg>
                    <p name="loginUser" class="loginUser"><?= $user === null ? 0 : $user->getLogin() ?></p>
                    <a href="?ctrl=account" class="modif">Modifier le compte</a>
                </div>
                <div class="logOut">
                    <a href="index.php?ctrl=main" class="btnlogout">
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

            <div class="friend">
                <div class="friend-title">
                    <legend>Amis (<?= $nb ?>)</legend>
                    <button class="addFriend" name="addFriend" id="addFriend"
                            user-id="<?= $user === null ? 0 : $user->getId() ?>">+ Ajouter un ami
                    </button>
                </div>
                <div class="friend-list">

                    <!-- Ajouter les amis ici -->
                    <?php
                    foreach ($friends as $friend) :
                        ?>
                        <!-- Squelette ami -->
                        <div class="div-aFriend">
                            <button class="aFriend" name="aFriend">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#00"
                                     class="bi bi-person-fill" viewBox="0 0 16 16">
                                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                                </svg>
                                <p><?= $friend->getLogin() ?></p>
                            </button>
                            <!-- Bouton pop-up -->
                            <button class="removeFriend" name="removeFriend" friend-id="<?= $friend->getId() ?>"
                                    user-id="<?= $user === null ? 0 : $user->getId() ?>">
                                Retirer l'ami
                            </button>
                        </div>
                        <!-- Fin squelette ami -->
                    <?php
                    endforeach;
                    ?>

                </div>

            </div>

            <div class="frame-coms">
                <legend>Commentaires récents</legend>
                <div class="coms">
                    <div class="btn-newComs">
                        <button class="myCom-button" id="myCom-button">
                            Vous
                        </button>
                        <button class="friend-button" id="friend-button">
                            Vos amis
                        </button>
                    </div>
                    <div class="coms-list">

                        <div class="myComs-list" id="myComs-list">
                            <!--------- Affichage des commentaires du user -->
                            <!-- Squelette du commentaire -->
                            <?php
                            if ($myComments != null): ?>
                                <?php
                                foreach ($myComments as $comment): ?>
                                    <article class="comment">
                    <span class="com-part1">
                        <p><?= $comment->getHouse()->getName() ?></p>
                        <span><?= $comment->getStars() ?></span>
                    </span>
                            <p><?= $comment->getCreationDate() ?></p>
                            <p><?= htmlspecialchars($comment->getContent()) ?></p>
                            <span class="grade">
                            <!-- Avis des commentaires -->
                            <form method="post">
                                <input class="btnUp" type="radio" id="thumbsUp-<?= $comment->getId() ?>"
                                       name="thumbs" value="like" comment-id="<?= $comment->getId() ?>" hidden>
                                <label class="labelUp" for="thumbsUp-<?= $comment->getId() ?>"
                                       data-comment-id="<?= $comment->getId() ?>" data-action="like">
                                    <p>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="grey"
                                         class="thumbsUp" viewBox="0 0 16 16">
                                        <path d="M6.956 1.745C7.021.81 7.908.087 8.864.325l.261.066c.463.116.874.456 1.012.965.22.816.533 2.511.062 4.51a10 10 0 0 1 .443-.051c.713-.065 1.669-.072 2.516.21.518.173.994.681 1.2 1.273.184.532.16 1.162-.234 1.733q.086.18.138.363c.077.27.113.567.113.856s-.036.586-.113.856c-.039.135-.09.273-.16.404.169.387.107.819-.003 1.148a3.2 3.2 0 0 1-.488.901c.054.152.076.312.076.465 0 .305-.089.625-.253.912C13.1 15.522 12.437 16 11.5 16H8c-.605 0-1.07-.081-1.466-.218a4.8 4.8 0 0 1-.97-.484l-.048-.03c-.504-.307-.999-.609-2.068-.722C2.682 14.464 2 13.846 2 13V9c0-.85.685-1.432 1.357-1.615.849-.232 1.574-.787 2.132-1.41.56-.627.914-1.28 1.039-1.639.199-.575.356-1.539.428-2.59z"/>
                                    </svg>
                                        <span id="like-count-<?= $comment->getId() ?>"><?= $comment->getLikes() ?></span>
                                    </p>
                                </label>


                                <!-- Bouton Dislike -->
                                    <input class="btnDown" type="radio" id="thumbsDown-<?= $comment->getId() ?>"
                                           name="thumbs" value="dislike" comment-id="<?= $comment->getId() ?>" hidden>
                                    <label class="labelDown" for="thumbsDown-<?= $comment->getId() ?>"
                                           data-comment-id="<?= $comment->getId() ?>" data-action="dislike">
                                    <p>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="grey"
                                         class="thumbsDown" viewBox="0 0 16 16">
                                        <path d="M6.956 14.534c.065.936.952 1.659 1.908 1.42l.261-.065a1.38 1.38 0 0 0 1.012-.965c.22-.816.533-2.512.062-4.51q.205.03.443.051c.713.065 1.669.071 2.516-.211.518-.173.994-.68 1.2-1.272a1.9 1.9 0 0 0-.234-1.734c.058-.118.103-.242.138-.362.077-.27.113-.568.113-.856 0-.29-.036-.586-.113-.857a2 2 0 0 0-.16-.403c.169-.387.107-.82-.003-1.149a3.2 3.2 0 0 0-.488-.9c.054-.153.076-.313.076-.465a1.86 1.86 0 0 0-.253-.912C13.1.757 12.437.28 11.5.28H8c-.605 0-1.07.08-1.466.217a4.8 4.8 0 0 0-.97.485l-.048.029c-.504.308-.999.61-2.068.723C2.682 1.815 2 2.434 2 3.279v4c0 .851.685 1.433 1.357 1.616.849.232 1.574.787 2.132 1.41.56.626.914 1.28 1.039 1.638.199.575.356 1.54.428 2.591"/>
                                    </svg>
                                        <span id="dislike-count-<?= $comment->getId() ?>"><?= $comment->getDislikes() ?></span>
                                    </p>
                                </label>
                            </form>
                        </span>
                                    </article>
                                <?php
                                endforeach; ?>

                            <?php
                            else: ?>
                                <p class="noComment">Aucun commentaire posté </p>
                            <?php
                            endif; ?>
                        </div>
                        <!-- Les commentaires des amis -->
                        <div class="friendsComs-list" id="friendsComs-list">
                            <!-- Squelette du commentaire -->
                            <?php
                            if ($comments != null): ?>
                                <?php
                                foreach ($comments as $comment): ?>
                                    <article class="comment">
                    <span class="com-part1">
                        <p><?= $comment->getHouse()->getName() ?></p>
                        <span><?= $comment->getStars() ?></span>
                    </span>
                            <p><?= $comment->getOwner()->getLogin() ?> <?= $comment->getCreationDate() ?></p>
                            <p><?= htmlspecialchars($comment->getContent()) ?></p>
                            <span class="grade">
                            <!-- Avis des commentaires -->
                            <form method="post">
                                <input class="btnUp" type="radio" id="thumbsUp-<?= $comment->getId() ?>"
                                       name="thumbs" value="like" comment-id="<?= $comment->getId() ?>" hidden>
                                <label class="labelUp" for="thumbsUp-<?= $comment->getId() ?>"
                                       data-comment-id="<?= $comment->getId() ?>" data-action="like">
                                    <p>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="grey"
                                         class="thumbsUp" viewBox="0 0 16 16">
                                        <path d="M6.956 1.745C7.021.81 7.908.087 8.864.325l.261.066c.463.116.874.456 1.012.965.22.816.533 2.511.062 4.51a10 10 0 0 1 .443-.051c.713-.065 1.669-.072 2.516.21.518.173.994.681 1.2 1.273.184.532.16 1.162-.234 1.733q.086.18.138.363c.077.27.113.567.113.856s-.036.586-.113.856c-.039.135-.09.273-.16.404.169.387.107.819-.003 1.148a3.2 3.2 0 0 1-.488.901c.054.152.076.312.076.465 0 .305-.089.625-.253.912C13.1 15.522 12.437 16 11.5 16H8c-.605 0-1.07-.081-1.466-.218a4.8 4.8 0 0 1-.97-.484l-.048-.03c-.504-.307-.999-.609-2.068-.722C2.682 14.464 2 13.846 2 13V9c0-.85.685-1.432 1.357-1.615.849-.232 1.574-.787 2.132-1.41.56-.627.914-1.28 1.039-1.639.199-.575.356-1.539.428-2.59z"/>
                                    </svg>
                                        <span id="like-count-<?= $comment->getId() ?>"><?= $comment->getLikes() ?></span>
                                    </p>
                                </label>


                                <!-- Bouton Dislike -->
                                    <input class="btnDown" type="radio" id="thumbsDown-<?= $comment->getId() ?>"
                                           name="thumbs" value="dislike" comment-id="<?= $comment->getId() ?>" hidden>
                                    <label class="labelDown" for="thumbsDown-<?= $comment->getId() ?>"
                                           data-comment-id="<?= $comment->getId() ?>" data-action="dislike">
                                    <p>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="grey"
                                         class="thumbsDown" viewBox="0 0 16 16">
                                        <path d="M6.956 14.534c.065.936.952 1.659 1.908 1.42l.261-.065a1.38 1.38 0 0 0 1.012-.965c.22-.816.533-2.512.062-4.51q.205.03.443.051c.713.065 1.669.071 2.516-.211.518-.173.994-.68 1.2-1.272a1.9 1.9 0 0 0-.234-1.734c.058-.118.103-.242.138-.362.077-.27.113-.568.113-.856 0-.29-.036-.586-.113-.857a2 2 0 0 0-.16-.403c.169-.387.107-.82-.003-1.149a3.2 3.2 0 0 0-.488-.9c.054-.153.076-.313.076-.465a1.86 1.86 0 0 0-.253-.912C13.1.757 12.437.28 11.5.28H8c-.605 0-1.07.08-1.466.217a4.8 4.8 0 0 0-.97.485l-.048.029c-.504.308-.999.61-2.068.723C2.682 1.815 2 2.434 2 3.279v4c0 .851.685 1.433 1.357 1.616.849.232 1.574.787 2.132 1.41.56.626.914 1.28 1.039 1.638.199.575.356 1.54.428 2.591"/>
                                    </svg>
                                        <span id="dislike-count-<?= $comment->getId() ?>"><?= $comment->getDislikes() ?></span>
                                    </p>
                                </label>
                            </form>
                        </span>
                            </article>
                        <?php
                        endforeach; ?>
                        
                    <?php
                    else: ?>
                        <p class="noComment">Aucun commentaire posté </p>
                    <?php
                    endif; ?>
                    </div>
                    </div>

                </div>
            </div>


        </div>

    </div>

</div>
<!-- Pop-up ajout amis -->
<div class="popup-addFriend" id="popup-addFriend">
    <div class="popup-content-addFriend" id="popup-content-addFriend">
        <form class="form-friend" action="../index.php?ctrl=friend" method="post">
            <label for="nameFriend">Nom d'utilisateur de l'ami :</label>
            <input id="nameFriend" name="nameFriend" type="text">
            <p class="empty-name" id="empty-name">Veuillez renseigner un nom</p>
            <div id="friend-Suggestion">
                <!--  est remplit par scriptsearchFriend.php-->
            </div>
            <span>
                <button name="btnfriend" value="annuler" class="closePopup-addFriend"
                        id="closePopup-addFriend">Annuler</button>
                <button name="btnfriend" value="envoyer" type="button" class="changeContent-addFriend"
                        id="changeContent-addFriend">Envoyer</button>
            </span>
        </form>
    </div>
</div>
<div class="popup-sendFriend" id="popup-sendFriend">
    <div class="popup-content-sendFriend" id="popup-content-sendFriend">
        <p>Une demande d'ami à été envoyée !</p>
        <button class="closePopup-sendFriend" id="closePopup-sendFriend" type="button">OK</button>

    </div>
</div>
<!-- Pop-up retirer amis -->
<div class="popup-sureRemove" id="popup-sureRemove">
    <div class="popup-content-sureRemove" id="popup-content-sureRemove">
        <form class="form-sureRemove" action="">
            <p>Êtes-vous sûr⸱e de vouloir retirer cet utilisateur de vos amis ?</p>
            <span class="button-sureRemove">
                <button class="closePopup-sureRemove" id="closePopup-sureRemove">Annuler</button>
                <button class="changeContent-sureRemove" name="changeContent-sureRemove" id="changeContent-sureRemove">Retirer</button>
            </span>
        </form>
    </div>
</div>
<div class="popup-removeFriend" id="popup-removeFriend">
    <div class="popup-content-removeFriend" id="popup-content-removeFriend">
        <p>Cet utilisateur a été retiré de votre liste d'amis</p>
        <button class="closePopup-removeFriend" id="closePopup-removeFriend" type="button">OK</button>

    </div>
</div>
<!-- Script pour les amis -->
<script src="view/script/friend.script.js"></script>
<!-- Script pour les likes -->
<script src="view/script/like.script.js"></script>
</body>

</html>