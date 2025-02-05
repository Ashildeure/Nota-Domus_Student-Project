// Récupérer les éléments des pop-ups et des boutons
const popupProposition = document.getElementById('myPopupProposition');
const openPopupProposition = document.getElementById('openPopupProposition');
const closePopupProposition = document.getElementById('closePopupProposition');
const closePopupProposition2 = document.getElementById('closePopupProposition2');
const changeContentProposition = document.getElementById('changeContentProposition');
const popupContentProposition = document.getElementById('popupContentProposition');
const popupContentProposition2 = document.getElementById('popupContentProposition2');

// Récupération des éléments pour vérifier que le formulaire de proposition est remplis
const nameOh = document.getElementById('nameOh');
const nameOutstanding = document.getElementById('nameOutstanding');
const adressOh = document.getElementById('adressOh');
const choice = document.getElementById('choice');
let elementTab = [nameOh,nameOutstanding,adressOh,choice];
// Récupération des phrases à afficher
const verifContentNameOhProposition = document.getElementById('verifContentNameOhProposition');
const verifContentNameOutstandingProposition = document.getElementById('verifContentNameOutstandingProposition');
const verifContentAdressOhProposition = document.getElementById('verifContentAdressOhProposition');
const verifContentChoiceProposition = document.getElementById('verifContentChoiceProposition');
let verifTab = [verifContentNameOhProposition, verifContentNameOutstandingProposition, verifContentAdressOhProposition, verifContentChoiceProposition];

let canChangeContentProposition;

// Vérifier que les éléments sont complets
function checkForm() {
    canChangeContentProposition = true;
    for(i=0; i<4; i++) {
        if(elementTab[i].value.trim()==="") {
            verifTab[i].style.opacity = 1;
            canChangeContentProposition = false;
        } else {
            verifTab[i].style.opacity = 0;
        }
    }
}

// Ouvrir le premier pop-up
openPopupProposition.addEventListener('click', () => {
    elementTab.forEach(element => {
        element.value='';
    });
    // Réinitialisation des messages d'erreur
    verifTab.forEach(element => {
        element.style.opacity = 0;
    });
    popupProposition.style.display = 'flex'; // Afficher le premier pop-up
});

// Fermer le premier pop-up
closePopupProposition.addEventListener('click', () => {
    event.preventDefault();
    popupProposition.style.display = 'none'; // Cacher le premier pop-up
});

// Fermer le premier pop-up à partir du changement de contenu
closePopupProposition2.addEventListener('click', () => {
    event.preventDefault();
    popupContentProposition.style.display = 'flex';
    popupContentProposition2.style.display = 'none';
    popupProposition.style.display = 'none'; // Cacher le premier pop-up
});

// Fermer le premier pop-up en cliquant en dehors du contenu
popupProposition.addEventListener('click', (event) => {
    if (event.target === popupProposition) {
        popupProposition.style.display = 'none';
    }
});

// Changer le contenu du pop-up
changeContentProposition.addEventListener('click', () => {
    event.preventDefault();
//    checkForm();
//    if(canChangeContentProposition) {
        popupContentProposition.style.display = 'none';
        popupContentProposition2.style.display = 'flex';
//    }
});
