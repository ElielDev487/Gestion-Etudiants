<?php
/**
 * Fichier de connexion à la base de données
 * Utilise PDO pour une connexion sécurisée et réutilisable
 * 
 * À inclure dans toutes les pages PHP:
 * require_once(__DIR__ . '/config/database.php');
 */

try {
    // Configuration de la connexion
    $dsn = 'mysql:host=localhost;dbname=gestion_etudiants;charset=utf8mb4';
    $user = 'root';
    $password = '';
    
    // Création de l'objet PDO avec options de configuration
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,           // Lève des exceptions en cas d'erreur
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,     // Récupère les résultats sous forme de tableau associatif
        PDO::ATTR_PERSISTENT => false                          // Pas de connexion persistante
    ]);
    
} catch (PDOException $e) {
    // Affiche le message d'erreur en cas de problème de connexion
    die('Erreur de connexion à la base de données: ' . $e->getMessage());
}
?>