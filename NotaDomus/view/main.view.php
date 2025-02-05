<!DOCTYPE html>
<html lang="fr">

<head>
    <title>NotaDomus</title>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/design/styleMain.css">
    <link rel="icon" href="public/design/img/logo.png">

</head>

<body>
    <!-- Left Section with logo and image -->
    <div class="left-section">
        <img src="public/design/img/logo.png" alt="Logo">
        <h1>Bienvenue sur NotaDomus</h1>
        <p>Venez (re)découvrir les maisons d'illustres françaises</p>
    </div>

    <!-- Right Section with forms -->
    <div class="right-section">
        <div class="main">
            <input type="checkbox" id="chk" aria-hidden="true">

            <!-- Connexion  -->
            <div class="login">
                <form method="post">
                    <label for="chk" aria-hidden="true">Connexion</label>
                    <!-- <label for="chk" aria-hidden="true">Login</label> -->
                    <input type="text" name="login" placeholder="Nom d'utilisateur" required="">
                    <div id="password-login" class="pwd-input">
                        <!-- Mettre l'input #password en premier element fils (sinon ça casse le javascript) -->
                        <input id="password" type="password" name="password" placeholder="Mot de passe" required>
                        <img id="eye-icon-login" onclick="onEyeClick('login')" src="public/design/img/eye-closed.svg" alt="afficher">
                    </div>
                    <button id="btnconnecter" name="action" value="connecter" type="submit">Se Connecter</button>
                    <!-- Lien vers mot de passe oublié -->
                    <div class="MDP">
                        <a href="?ctrl=resetPassword" class="forgot-password">Mot de passe oublié ?</a>
                    </div>
                </form>

            </div>

            <!-- Inscription  -->
            <div class="signup">
                <form method="post" onsubmit="return validatePassword()">
                    <input type="hidden" name="ctrl" value="main">
                    <label for="chk" aria-hidden="true">Inscription</label>
                    <input type="text" name="login" placeholder="Nom d'utilisateur" required>
                    <input type="email" name="email" placeholder="Adresse email" required>
                    
                    <!-- Input de mot de passe -->
                    <div id="password-signup" class="pwd-input">
                        <input id="new-password" type="password" name="password" placeholder="Mot de passe" required>
                        <div class="guide">
                            <p class="password-tips">Votre mot de passe doit contenir au moins :</p>
                            <ul class="password-tips">
                                <li id="length">8 caractères</li>
                                <li id="Letter-case">Une majuscule et une minuscule</li>
                                <li id="number">Un chiffre</li>
                                <li id="special">Un caractère spécial</li>
                            </ul>
                        </div>
                        <img id="eye-icon-signup" onclick="onEyeClick('signup')" src="public/design/img/eye-closed.svg" alt="afficher">
                    </div>

                    <!-- Conditions Générales -->
                    <div class="CGU_container">
                        <input type="checkbox" id="CGU" required>
                        <label id="CGU_label" for="CGU">J'accepte&nbsp<a href="docs\CGU.pdf" target="_blank" rel="noopener noreferrer">les conditions d'utilisation</a></label>
                    </div>
                    <button id="btninscrire" name="action" value="inscrire" type="submit">S'inscrire</button>
                </form>
            </div>
        </div>
        <!-- Bouton sans connexion -->
        <div class="sans-connexion">

            <a href="?ctrl=userMap">
                <button name="action" value="sansconnecter" type="submit">Continuer sans connexion</button>
            </a>
        </div>
    </div>

    <script src="view/script/passwordVerif.script.js"></script>

</body>

</html>