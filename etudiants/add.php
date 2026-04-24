<?php
/**
 * Page d'ajout d'un étudiant
 * 
 * Affiche le formulaire d'ajout (GET)
 * Traite l'insertion des données (POST)
 */

// Démarrer la session
session_start();

// Inclure la connexion à la base de données
require_once(__DIR__ . '/../config/database.php');

// ============================================
// AFFICHAGE DU FORMULAIRE (GET)
// ============================================

if ($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    
    try {
        // Récupérer toutes les filières
        $stmt = $pdo->query('SELECT id, nom FROM filieres ORDER BY nom');
        $filieres = $stmt->fetchAll();
        
    } catch (PDOException $e) {
        $error = 'Erreur lors de la récupération des filières: ' . $e->getMessage();
    }
    
    // Afficher le formulaire
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ajouter un étudiant</title>
        <link rel="stylesheet" href="../assets/css/style.css">
    </head>
    <body>
        <div class="container">
            <h1>➕ Ajouter un nouvel étudiant</h1>
            
            <!-- Afficher un message d'erreur s'il y en a une -->
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
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
            
            <form action="add.php" method="POST" id="formAddEtudiant">
                
                <!-- Champ: Nom -->
                <div class="form-group">
                    <label for="nom">Nom *</label>
                    <input 
                        type="text" 
                        id="nom" 
                        name="nom" 
                        placeholder="Entrez le nom de l'étudiant"
                        value="<?php echo isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ''; ?>"
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
                        value="<?php echo isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : ''; ?>"
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
                                <?php echo (isset($_POST['filiere_id']) && $_POST['filiere_id'] == $filiere['id']) ? 'selected' : ''; ?>
                            >
                                <?php echo htmlspecialchars($filiere['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Bouton d'envoi -->
                <button type="submit">💾 Ajouter l'étudiant</button>
            </form>
            
            <!-- Lien pour revenir -->
            <div style="text-align: center; margin-top: 20px;">
                <a href="index.php" class="btn" style="width: auto; display: inline-block;">← Retour</a>
            </div>
        </div>
        
        <script src="../assets/js/script.js"></script>
    </body>
    </html>
    <?php
    exit;
}

// ============================================
// TRAITEMENT DES DONNÉES (POST)
// ============================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
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
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header('Location: add.php');
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
        
        // Message de succès
        $_SESSION['success'] = 'Étudiant ajouté avec succès!';
        
        // Rediriger vers la page principale (liste des étudiants)
        header('Location: index.php');
        exit;
        
    } catch (PDOException $e) {
        // Afficher le message d'erreur en cas de problème
        $_SESSION['errors'] = ['Erreur lors de l\'insertion: ' . $e->getMessage()];
        header('Location: add.php');
        exit;
    }
}
?>

