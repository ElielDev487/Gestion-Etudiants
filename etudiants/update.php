<?php
/**
 * Page de traitement - Modification d'un étudiant
 * 
 * Cette page:
 * - Affiche un formulaire pré-rempli avec les données de l'étudiant (si GET)
 * - Traite la modification des données (si POST)
 * - Valide les données côté serveur
 * - Met à jour la base de données
 * - Redirige vers la page principale
 */

// Démarrer la session
session_start();

// Inclure la connexion à la base de données
require_once(__DIR__ . '/../config/database.php');

// Récupérer l'ID de l'étudiant
$id = isset($_GET['id']) ? (int)$_GET['id'] : (isset($_POST['id']) ? (int)$_POST['id'] : 0);

// Vérifier que l'ID est valide
if ($id === 0) {
    $_SESSION['errors'] = ['ID étudiant invalide'];
    header('Location: ../index.php');
    exit;
}

// ============================================
// RÉCUPÉRATION DES DONNÉES (AFFICHAGE DU FORMULAIRE)
// ============================================

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    try {
        // Récupérer les données de l'étudiant
        $stmt = $pdo->prepare('SELECT * FROM etudiants WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $etudiant = $stmt->fetch();
        
        if (!$etudiant) {
            $_SESSION['errors'] = ['Étudiant non trouvé'];
            header('Location: ../index.php');
            exit;
        }
        
        // Récupérer toutes les filières
        $stmt = $pdo->query('SELECT id, nom FROM filieres ORDER BY nom');
        $filieres = $stmt->fetchAll();
        
    } catch (PDOException $e) {
        $_SESSION['errors'] = ['Erreur lors de la récupération: ' . $e->getMessage()];
        header('Location: ../index.php');
        exit;
    }
    
    // Afficher le formulaire de modification
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Modifier un étudiant</title>
        <link rel="stylesheet" href="../assets/css/style.css">
    </head>
    <body>
        <div class="container">
            <h1>✏️ Modifier un étudiant</h1>
            
            <form action="update.php" method="POST" id="formAddEtudiant">
                <!-- Champ caché pour l'ID -->
                <input type="hidden" name="id" value="<?php echo $etudiant['id']; ?>">
                
                <!-- Champ: Nom -->
                <div class="form-group">
                    <label for="nom">Nom *</label>
                    <input 
                        type="text" 
                        id="nom" 
                        name="nom" 
                        value="<?php echo htmlspecialchars($etudiant['nom']); ?>"
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
                        value="<?php echo htmlspecialchars($etudiant['prenom']); ?>"
                        placeholder="Entrez le prénom de l'étudiant"
                        required
                    >
                    <div class="error-message" id="errorPrenom">Le prénom est obligatoire</div>
                </div>
                
                <!-- Champ: Filière -->
                <div class="form-group">
                    <label for="filiere_id">Filière *</label>
                    <select id="filiere_id" name="filiere_id" required>
                        <option value="">-- Sélectionnez une filière --</option>
                        
                        <?php foreach ($filieres as $filiere): ?>
                            <option 
                                value="<?php echo $filiere['id']; ?>"
                                <?php echo ($filiere['id'] == $etudiant['filiere_id']) ? 'selected' : ''; ?>
                            >
                                <?php echo htmlspecialchars($filiere['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Bouton d'envoi -->
                <button type="submit">💾 Enregistrer les modifications</button>
            </form>
            
            <!-- Lien pour revenir -->
            <div style="text-align: center; margin-top: 20px;">
                <a href="../index.php" class="btn" style="width: auto; display: inline-block;">← Retour</a>
            </div>
        </div>
        
        <script src="../assets/js/script.js"></script>
    </body>
    </html>
    <?php
    exit;
}

// ============================================
// TRAITEMENT DE LA MODIFICATION (MISE À JOUR EN BASE)
// ============================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Récupérer et nettoyer les données
    $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
    $prenom = isset($_POST['prenom']) ? trim($_POST['prenom']) : '';
    $filiere_id = isset($_POST['filiere_id']) ? (int)$_POST['filiere_id'] : 0;
    
    // Valider les données
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
        $_SESSION['errors'] = $errors;
        header('Location: update.php?id=' . $id);
        exit;
    }
    
    try {
        // Préparer la requête de mise à jour
        $stmt = $pdo->prepare('
            UPDATE etudiants 
            SET nom = :nom, prenom = :prenom, filiere_id = :filiere_id
            WHERE id = :id
        ');
        
        // Exécuter la requête
        $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':filiere_id' => $filiere_id,
            ':id' => $id
        ]);
        
        // Message de succès
        $_SESSION['success'] = 'Étudiant modifié avec succès!';
        
        // Rediriger vers la page principale
        header('Location: ../index.php');
        exit;
        
    } catch (PDOException $e) {
        $_SESSION['errors'] = ['Erreur lors de la mise à jour: ' . $e->getMessage()];
        header('Location: update.php?id=' . $id);
        exit;
    }
}
?>
