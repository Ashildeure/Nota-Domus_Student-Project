const passwordInput = document.getElementById("new-password");
const confirmPassword = document.getElementById("confirm-password");
const notSamePass = document.getElementById("notSamePass");
const setCurrentPass = document.getElementById("setCurrentPass");


// Liste des critères
const criteriaList = {
    length: document.getElementById("length"),
    letter_case: document.getElementById("Letter-case"),
    number: document.getElementById("number"),
    special: document.getElementById("special"),
};

// Vérification des critères en temps réel
passwordInput.addEventListener("input", function () {
    const password = passwordInput.value;

    // Vérifier la longueur
    criteriaList.length.classList.toggle("valid", password.length >= 8);

    // Vérifier les majuscules et minuscules
    const hasUpper = /[A-Z]/.test(password);
    const hasLower = /[a-z]/.test(password);
    criteriaList.letter_case.classList.toggle("valid", hasUpper && hasLower);

    // Vérifier les chiffres
    criteriaList.number.classList.toggle("valid", /[0-9]/.test(password));

    // Vérifier les caractères spéciaux
    criteriaList.special.classList.toggle("valid", /[\W_]/.test(password));
});

// Fonction pour afficher ou masquer le mot de passe
function onEyeClick(location) {
    passwordField = document.getElementById("password-" + location).firstElementChild;
    eyeIcon = document.getElementById("eye-icon-" + location);
    if (passwordField.type === "password") {
        passwordField.type = "text";
        eyeIcon.src = "public/design/img/eye-open.svg";
    } else {
        passwordField.type = "password";
        eyeIcon.src = "public/design/img/eye-closed.svg";
    }
}

// Vérifier si tous les critères sont valides
function isPasswordValid() {
    return Object.values(criteriaList).every((criterion) => criterion.classList.contains("valid"));
}

// Bloquer la soumission si le mot de passe est invalide
function validatePasswordUpdate() {
    
    // Afficher le texte si les deux input de changement de password sont différents
    if (confirmPassword.value !== passwordInput.value) {
        notSamePass.style.display = "flex";
        return false;
    }
    // Afficher le texte si le mdp actuel n'a pas été renseigné
    if (setCurrentPass.value === null) {
        setCurrentPass.style.display = "flex";
        return false;
    }
    if (!isPasswordValid()) {
        // Afficher les instructions si les critères ne sont pas respectés
        document.querySelector(".guide").style.display = "block";
        return false;
    }
    return true;
    
}