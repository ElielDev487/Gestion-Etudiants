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
