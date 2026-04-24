<?php
/**
 * Page de traitement - Ajout d'un étudiant
 * 
 * Cette page:
 * - Reçoit les données du formulaire en POST
 * - Valide les données côté serveur
 * - Insère les données dans la base de données
 * - Redirige vers la page principale
 */

// Inclure la connexion à la base de données
require_once(__DIR__ . '/../config/database.php');

// Vérifier que la requête est en POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Si ce n'est pas une requête POST, rediriger vers la page principale
    header('Location: ../index.php');
    exit;
}

// Récupérer et nettoyer les données du formulaire
$nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
$prenom = isset($_POST['prenom']) ? trim($_POST['prenom']) : '';
$filiere_id = isset($_POST['filiere_id']) ? (int)$_POST['filiere_id'] : 0;

// Valider les données côté serveur
$errors = [];

if (empty($nom)) {
    $errors[] = 'Le nom est obligatoire';
}

if (empty($prenom)) {
    $errors[] = 'Le prénom est obligatoire';
}

if ($filiere_id === 0) {
    $errors[] = 'Veuillez sélectionner une filière';
}

// Si des erreurs, rediriger avec message
if (!empty($errors)) {
    // Stocker les erreurs en session pour les afficher
    session_start();
    $_SESSION['errors'] = $errors;
    $_SESSION['form_data'] = $_POST;
    header('Location: ../index.php');
    exit;
}

try {
    // Préparer la requête d'insertion (requête sécurisée)
    $stmt = $pdo->prepare('
        INSERT INTO etudiants (nom, prenom, filiere_id)
        VALUES (:nom, :prenom, :filiere_id)
    ');
    
    // Exécuter la requête avec les paramètres
    $stmt->execute([
        ':nom' => $nom,
        ':prenom' => $prenom,
        ':filiere_id' => $filiere_id
    ]);
    
    // Démarrer la session pour stocker le message de succès
    session_start();
    $_SESSION['success'] = 'Étudiant ajouté avec succès!';
    
    // Rediriger vers la page principale
    header('Location: ../index.php');
    exit;
    
} catch (PDOException $e) {
    // Afficher le message d'erreur en cas de problème
    session_start();
    $_SESSION['errors'] = ['Erreur lors de l\'insertion: ' . $e->getMessage()];
    header('Location: ../index.php');
    exit;
}
?>
