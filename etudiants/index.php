<?php
/**
 * Page principale - Liste des étudiants
 * 
 * Fonctionnalités:
 * - Récupère et affiche la liste de tous les étudiants
 * - Affiche les boutons modifier et supprimer
 * - Gère les messages de succès/erreur
 */

// Démarrer la session pour gérer les messages
session_start();

// Inclure la connexion à la base de données
require_once(__DIR__ . '/../config/database.php');

// Variable pour stocker les étudiants
$etudiants = [];

try {
    // Récupérer tous les étudiants avec leur filière (jointure)
    $stmt = $pdo->query('
        SELECT e.id, e.nom, e.prenom, f.nom AS filiere_nom
        FROM etudiants e
        LEFT JOIN filieres f ON e.filiere_id = f.id
        ORDER BY e.nom, e.prenom
    ');
    $etudiants = $stmt->fetchAll();
} catch (PDOException $e) {
    // Afficher le message d'erreur si la requête échoue
    $error = 'Erreur lors de la récupération: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Étudiants</title>
    <!-- Lier le fichier CSS commun -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>📚 Gestion des Étudiants</h1>

        <!-- Afficher un message d'erreur général s'il y en a une -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Afficher les messages de succès -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                ✅ <?php echo $_SESSION['success']; ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- Afficher les messages d'erreur de la session -->
        <?php if (isset($_SESSION['errors'])): ?>
            <div class="alert alert-danger">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <div>❌ <?php echo $error; ?></div>
                <?php endforeach; ?>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <!-- Bouton d'accès au formulaire d'ajout -->
        <div style="margin-bottom: 30px; text-align: center;">
            <a href="add.php" class="btn" style="width: auto; display: inline-block; background: #27ae60;">
                ➕ Ajouter un étudiant
            </a>
        </div>

        <!-- ========================================
             SECTION: RECHERCHE
             ======================================== -->
        <div style="margin-bottom: 30px;">
            <div class="form-group">
                <label for="searchInput">🔍 Rechercher un étudiant</label>
                <input 
                    type="text" 
                    id="searchInput" 
                    placeholder="Recherchez par nom, prénom ou filière..."
                    style="margin-bottom: 0;"
                >
            </div>
        </div>

        <!-- ========================================
             SECTION: LISTE DES ÉTUDIANTS
             ======================================== -->
        <h2>Liste des étudiants</h2>
        
        <?php if (empty($etudiants)): ?>
            <!-- Afficher un message si aucun étudiant -->
            <div class="alert alert-info">
                ℹ️ Aucun étudiant enregistré pour le moment.
            </div>
        <?php else: ?>
            <!-- Tableau des étudiants -->
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Filière</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($etudiants as $etudiant): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($etudiant['nom']); ?></td>
                                <td><?php echo htmlspecialchars($etudiant['prenom']); ?></td>
                                <td><?php echo htmlspecialchars($etudiant['filiere_nom']); ?></td>
                                <td>
                                    <div class="actions">
                                        <!-- Bouton Modifier -->
                                        <a 
                                            href="update.php?id=<?php echo $etudiant['id']; ?>" 
                                            class="btn btn-warning"
                                        >
                                            ✏️ Modifier
                                        </a>
                                        
                                        <!-- Bouton Supprimer avec confirmation -->
                                        <a 
                                            href="delete.php?id=<?php echo $etudiant['id']; ?>" 
                                            class="btn btn-danger"
                                            onclick="return confirmerSuppression(<?php echo $etudiant['id']; ?>)"
                                        >
                                            🗑️ Supprimer
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

    </div>

    <!-- Lier le fichier JavaScript -->
    <script src="../assets/js/script.js"></script>
</body>
</html>
