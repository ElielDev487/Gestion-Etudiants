/**
 * Fichier JavaScript - Gestion des étudiants
 * 
 * Fonctionnalités:
 * - Validation des formulaires (ajout et modification)
 * - Confirmation avant suppression
 * - Gestion des messages d'erreur
 */

// ============================================
// VALIDATION DES FORMULAIRES
// ============================================

/**
 * Valide le formulaire d'ajout/modification d'étudiant
 * Vérifie que le nom et le prénom sont remplis
 * 
 * @returns {boolean} true si valide, false sinon
 */
function validerFormulaire() {
    // Récupérer les valeurs des champs
    const nom = document.getElementById('nom').value.trim();
    const prenom = document.getElementById('prenom').value.trim();
    
    // Réinitialiser les messages d'erreur
    document.getElementById('errorNom').classList.remove('show');
    document.getElementById('errorPrenom').classList.remove('show');
    
    let isValid = true;
    
    // Vérifier que le nom est rempli
    if (nom === '') {
        document.getElementById('errorNom').classList.add('show');
        isValid = false;
    }
    
    // Vérifier que le prénom est rempli
    if (prenom === '') {
        document.getElementById('errorPrenom').classList.add('show');
        isValid = false;
    }
    
    return isValid;
}

/**
 * Intercepte l'envoi du formulaire et valide les données
 * Appelée lors du chargement de la page
 */
document.addEventListener('DOMContentLoaded', function() {
    
    // Cibler le formulaire d'ajout/modification d'étudiant
    const form = document.getElementById('formAddEtudiant');
    
    if (form) {
        // Intercepter l'envoi du formulaire
        form.addEventListener('submit', function(e) {
            // Empêcher l'envoi par défaut
            e.preventDefault();
            
            // Valider le formulaire
            if (validerFormulaire()) {
                // Si valide, envoyer le formulaire
                form.submit();
            }
            // Si invalide, l'envoi est bloqué et les messages d'erreur s'affichent
        });
    }
    
    // Ajouter l'écouteur pour la recherche d'étudiants
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', filtrerEtudiants);
    }
});

// ============================================
// CONFIRMATION AVANT SUPPRESSION
// ============================================

/**
 * Affiche une confirmation avant de supprimer un étudiant
 * 
 * @param {number} id - L'ID de l'étudiant à supprimer
 * @returns {boolean} true si confirmation, false sinon
 */
function confirmerSuppression(id) {
    return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ? Cette action est irréversible.');
}

// ============================================
// RECHERCHE D'ÉTUDIANTS
// ============================================

/**
 * Filtre le tableau des étudiants en temps réel
 * Recherche dans: nom, prénom, filière
 */
function filtrerEtudiants() {
    const searchInput = document.getElementById('searchInput');
    
    if (!searchInput) return; // Si le champ de recherche n'existe pas, quitter
    
    const searchTerm = searchInput.value.toLowerCase();
    const tableRows = document.querySelectorAll('table tbody tr');
    let visibleCount = 0;
    
    tableRows.forEach(row => {
        // Récupérer le texte de chaque colonne
        const nom = row.cells[0].textContent.toLowerCase();
        const prenom = row.cells[1].textContent.toLowerCase();
        const filiere = row.cells[2].textContent.toLowerCase();
        
        // Vérifier si le terme de recherche se trouve dans n'importe quelle colonne
        const matches = nom.includes(searchTerm) || 
                       prenom.includes(searchTerm) || 
                       filiere.includes(searchTerm);
        
        if (searchTerm === '' || matches) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Afficher un message si aucun résultat
    const table = document.querySelector('table');
    let noResultsRow = document.getElementById('noResultsRow');
    
    if (visibleCount === 0 && table) {
        if (!noResultsRow) {
            noResultsRow = document.createElement('tr');
            noResultsRow.id = 'noResultsRow';
            noResultsRow.innerHTML = '<td colspan="4" style="text-align: center; padding: 30px; color: #6b7280;">❌ Aucun étudiant trouvé</td>';
            table.querySelector('tbody').appendChild(noResultsRow);
        }
        noResultsRow.style.display = '';
    } else if (noResultsRow) {
        noResultsRow.style.display = 'none';
    }
}
