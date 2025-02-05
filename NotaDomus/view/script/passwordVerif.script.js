const passwordInput = document.getElementById("new-password");

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
    let passwordField = document.getElementById("password-" + location).firstElementChild;
    let eyeIcon = document.getElementById("eye-icon-" + location);
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
function validatePassword() {
    if (!isPasswordValid()) {
        // Afficher les instructions si les critères ne sont pas respectés
        passwordInput.focus();
        return false;
    }
    let regExp = /\S+@\S+\.\S+/;
    if (!regExp.test(document.getElementById("email").value)) {
        alert("Il semblerait que l'adresse email n'est pas valide.")
        return false;
    }
    return true;
}
