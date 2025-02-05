// Récupérer les éléments des pop-ups et des boutons
const popupVisitor = document.getElementById('popupVisitor');
const openPopupVisitor = document.getElementsByName('openPopupVisitor');
const closePopupVisitor = document.getElementById('closePopupVisitor');

// Ouvrir le premier pop-up
openPopupVisitor.forEach(element => {
    element.addEventListener('click', () => {
        popupVisitor.style.display = 'flex'; // Afficher le premier pop-up
    });
});

// Fermer le pop-up
closePopupVisitor.addEventListener('click', () => {
    event.preventDefault();
    popupVisitor.style.display = 'none'; // Cacher le pop-up
});

// Fermer le premier pop-up en cliquant en dehors du contenu
popupVisitor.addEventListener('click', (event) => {
    if (event.target === popupVisitor) {
        popupVisitor.style.display = 'none';
    }
});