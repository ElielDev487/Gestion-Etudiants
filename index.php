<?php
/**
 * Page principale - Gestion des étudiants
 * Affiche le formulaire d'ajout d'un étudiant
 * 
 * Fonctionnalités:
 * - Récupère les filières depuis la base de données
 * - Affiche un formulaire de création d'étudiant
 * - Affiche la liste des étudiants (TODO: Partie 8)
 */

// Démarrer la session pour gérer les messages
session_start();

// Inclure la connexion à la base de données
require_once(__DIR__ . '/config/database.php');

// Variable pour stocker les filières
$filieres = [];

try {
    // Récupérer toutes les filières de la base de données
    $stmt = $pdo->query('SELECT id, nom FROM filieres ORDER BY nom');
    $filieres = $stmt->fetchAll();
} catch (PDOException $e) {
    // Afficher le message d'erreur si la requête échoue
    $error = 'Erreur lors de la récupération des filières: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Étudiants</title>
    <!-- Lier le fichier CSS commun -->
    <link rel="stylesheet" href="assets/css/style.css">
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

        <!-- ========================================
             SECTION: FORMULAIRE D'AJOUT
             ======================================== -->
        <h2>Ajouter un nouvel étudiant</h2>
        
        <!-- Formulaire d'ajout d'un étudiant -->
        <form action="etudiants/add.php" method="POST" id="formAddEtudiant">
            
            <!-- Champ: Nom -->
            <div class="form-group">
                <label for="nom">Nom *</label>
                <input 
                    type="text" 
                    id="nom" 
                    name="nom" 
                    placeholder="Entrez le nom de l'étudiant"
                    required
                >
                <div class="error-message" id="errorNom">Le nom est obligatoire</div>
            </div>

            <!-- Champ: Prénom -->
            <div class="form-group">
                <label for="prenom">Prénom *</label>
                <input 
                    type="text" 
                    id="prenom" 
                    name="prenom" 
                    placeholder="Entrez le prénom de l'étudiant"
                    required
                >
                <div class="error-message" id="errorPrenom">Le prénom est obligatoire</div>
            </div>

            <!-- Champ: Filière (Liste déroulante dynamique) -->
            <div class="form-group">
                <label for="filiere_id">Filière *</label>
                <select id="filiere_id" name="filiere_id" required>
                    <option value="">-- Sélectionnez une filière --</option>
                    
                    <!-- Afficher les filières récupérées de la base de données -->
                    <?php foreach ($filieres as $filiere): ?>
                        <option value="<?php echo $filiere['id']; ?>">
                            <?php echo htmlspecialchars($filiere['nom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Bouton d'envoi -->
            <button type="submit">➕ Ajouter l'étudiant</button>
        </form>

        <!-- ========================================
             SECTION: LISTE DES ÉTUDIANTS
             TODO: À implémenter à la Partie 8
             ======================================== -->
        <!-- La liste des étudiants sera affichée ici -->

    </div>

    <!-- Lier le fichier JavaScript -->
    <script src="assets/js/script.js"></script>
</body>
</html>
