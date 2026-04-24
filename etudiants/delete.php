<?php
/**
 * Page de suppression d'un étudiant
 * 
 * Cette page:
 * - Reçoit l'ID de l'étudiant à supprimer
 * - Supprime l'étudiant de la base de données
 * - Redirige vers la page principale
 */

// Démarrer la session
session_start();

// Inclure la connexion à la base de données
require_once(__DIR__ . '/../config/database.php');

// Récupérer l'ID de l'étudiant
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Vérifier que l'ID est valide
if ($id === 0) {
    $_SESSION['errors'] = ['ID étudiant invalide'];
    header('Location: ../index.php');
    exit;
}

try {
    // Préparer la requête de suppression
    $stmt = $pdo->prepare('DELETE FROM etudiants WHERE id = :id');
    
    // Exécuter la requête
    $result = $stmt->execute([':id' => $id]);
    
    // Vérifier si la suppression a été effectuée
    if ($stmt->rowCount() > 0) {
        $_SESSION['success'] = 'Étudiant supprimé avec succès!';
    } else {
        $_SESSION['errors'] = ['Étudiant non trouvé'];
    }
    
    // Rediriger vers la page principale
    header('Location: ../index.php');
    exit;
    
} catch (PDOException $e) {
    $_SESSION['errors'] = ['Erreur lors de la suppression: ' . $e->getMessage()];
    header('Location: ../index.php');
    exit;
}
?>
