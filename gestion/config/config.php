<?php
$servername = "localhost";
$username = "root"; // ou "root"
$password = ""; // ou ""
$dbname = "ocp_gestion";

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}
// echo "Connexion réussie";
?>
