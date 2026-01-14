document.getElementById('search-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Empêche le formulaire de se soumettre normalement

    // Récupérer les valeurs des champs description et typerec
    const descriptionValue = document.getElementById('description').value;
    const typerecValue = document.getElementById('typerec').value;

    // Envoyer une requête AJAX au backend avec les valeurs de recherche
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `/reclamation/search?description=${descriptionValue}&typerec=${typerecValue}`);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Traitement des résultats reçus du backend
                const response = JSON.parse(xhr.responseText);
                // Mettre à jour le tableau des réclamations avec les résultats de la recherche
                updateReclamationTable(response.reclamations);
            } else {
                // Gérer les erreurs éventuelles
            }
        }
    };
    xhr.send();
});
function updateReclamationTable(reclamations) {
    const tableBody = document.querySelector('.table tbody');
    tableBody.innerHTML = ''; // Efface le contenu actuel du tableau

    if (reclamations.length === 0) {
        const newRow = tableBody.insertRow();
        const cell = newRow.insertCell();
        cell.colSpan = '5';
        cell.textContent = 'Aucun résultat trouvé';
    } else {
        reclamations.forEach(reclamation => {
            const newRow = tableBody.insertRow();
            newRow.innerHTML = `
                <td>${reclamation.idrec}</td>
                <td>${reclamation.date}</td>
                <td>${reclamation.description}</td>
                <td>${reclamation.typerec}</td>
                <td>${reclamation.etatrec}</td>
                <td>
                    <!-- Boutons Show, Exporter vers Excel, Répondre... -->
                </td>
            `;
        });
    }
}