<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changer le mot de passe</title>
    <link rel="stylesheet" href="public/design/styleUpdateAccount.css">
    <link rel="icon" href="public/design/img/logo.png">
</head>

<body>
    <div class="container">

        <div class="profile-picture">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
            </svg>

            <p><?= $user === null ? 0 : $user->getLogin() ?></p>
        </div>


        <form class="password-change-form" method="post" onsubmit="return validatePasswordUpdate()">

            <!-- Mail -->
            <div class="mail">
                <label for="input-mail">Adresse mail :</label>
                <input type="text" id="input-mail" name="input-mail"
                    value="<?= $user === null ? "" : $user->getEmail() ?>" disabled>
            </div>

            <!-- Ancien MDP -->
            <div class="currentPass">
                <label for="old-password">Mot de passe actuel :</label>

                <div id="password-old" class="pwd-input">
                    <!-- Mettre l'input #password en premier element fils (sinon ça casse le javascript) -->
                    <input id="old-password" type="password" name="old-password" placeholder="Entrez votre mot de passe actuel" required>
                    <img id="eye-icon-old" onclick="onEyeClick('old')" src="public/design/img/eye-closed.svg" alt="afficher">
                </div>
                <p class="setCurrentPass" id="setCurrentPass"> Vous devez renseigner votre mot de passe actuel avant de procéder à toute modification de vos informations </p>
            </div>


            <!-- Nouveaux MDPs -->
            <div class="newPass">

                <!-- Nouveau MDP -->
                <label for="new-password">Nouveau mot de passe :</label>
                <div id="password-new" class="pwd-input">
                    <input type="password" id="new-password" name="new-password" placeholder="Entrez le nouveau mot de passe" required>
                    <img id="eye-icon-new" onclick="onEyeClick('new')" src="public/design/img/eye-closed.svg" alt="afficher">
                </div>


                <!-- Guide -->
                <div class="guide">
                    <p class="password-tips">Votre mot de passe doit contenir au moins :</p>
                    <ul class="password-tips">
                        <li id="length">8 caractères</li>
                        <li id="Letter-case">Une majuscule et une minuscule</li>
                        <li id="number">Un chiffre</li>
                        <li id="special">Un caractère spécial</li>
                    </ul>
                </div>


                <!--Confirm Nouveau MDP -->
                <label for="confirm-password">Confirmez le mot de passe :</label>
                <section id="password-confirm-section">
                    <div id="password-confirm" class="pwd-input">
                        <input type="password" id="password-confirm" name="confirm-password" placeholder="Confirmez le mot de passe" required>
                        <img id="eye-icon-confirm" onclick="onEyeClick('confirm')" src="public/design/img/eye-closed.svg" alt="afficher">
                    </div>
                    <p class="invalid" id="notSamePass" >Les mots de passe ne correspondent pas</p>
                </section>

            </div>

            <!-- Les Boutons -->
            <div class="btnAccount">
                <a href="?ctrl=userMap" id="cancel">Annuler</a>
                <button type="submit">Enregistrer</button>
            </div>
        </form>
    </div>
    </div>
    <script src="view/script/accountPasswordVerif.script.js"></script>
</body>

</html>