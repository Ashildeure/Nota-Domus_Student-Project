const searchHouse = document.getElementById('search-house')// text feild ou et fait la recherche
const houseSuggestionDiv = document.getElementById('house-Suggestion')

let debounceTimeoutHouse;
searchHouse.addEventListener('keydown', function (event) {
    // Annule le dernier timeout si l'utilisateur continue de taper
    // Cela évite d'exécuter la requête trop souvent
    houseSuggestionDiv.style.display = 'flex';
    clearTimeout(debounceTimeoutHouse);

    // Crée un nouveau timeout pour exécuter la requête après une courte pause
    debounceTimeoutHouse = setTimeout(() => {
        // Récupère la valeur actuelle du champ de saisie
        console.log(searchHouse.value);
        // Récupère la valeur actuelle du champ de saisie
        const search =searchHouse.value;

        // Envoie une requête POST au serveur avec la recherche
        fetch('view/script/houseSearch.script.php', {
            method: 'POST',
            body: new URLSearchParams({
                'search': search // Envoie la valeur de recherche
            })
        })
            .then(response => {
                // Vérifie si la réponse est valide
                if (!response.ok) {
                    throw new Error('Erreur réseau : ' + response.statusText);
                }
                return response.text(); // Convertit la réponse en texte
            })
            .then(data => {
                // Met à jour le contenu de la div avec les résultats
                houseSuggestionDiv.innerHTML = data;

                // Ajout des gestionnaires d'événements après la mise à jour
                // je suis obliger de le faire la car avant les btn  n'etais pas cree
                const suggestionButtons = document.querySelectorAll('.name-House-Suggestion');
                suggestionButtons.forEach(button => {
                    button.addEventListener('click', event => {
                        event.preventDefault();
                        const value = button.textContent;
                        searchHouse.value = value; // Remplit le champ input avec la valeur
                    });
                });
            })
            .catch(error => {
                // Affiche une erreur en cas de problème avec la requête
                console.error('Erreur lors de la requête :', error);
                houseSuggestionDiv.innerHTML = '<p>Une erreur est survenue. Veuillez réessayer.</p>';
            });
        }, 300); // Définit une pause de 300 ms avant d'exécuter la requête
});

searchHouse.addEventListener('click', (event) => {
    if (event.target === searchHouse) {
        houseSuggestionDiv.style.display = 'none';
    }
})