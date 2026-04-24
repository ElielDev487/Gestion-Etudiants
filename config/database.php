<?php
// Configuration de la connexion PDO
try {
    $dsn = 'mysql:host=localhost;dbname=gestion_etudiants;charset=utf8mb4';
    $user = 'root';
    $password = '';
    
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_PERSISTENT => false
    ]);
    
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données: ' . $e->getMessage());
}
?>