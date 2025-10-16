<?php
session_start();
include '../config/config.php';

if(isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Requête pour vérifier l'utilisateur
    $sql = "SELECT * FROM Technicien WHERE userName='$username' AND password='$password'";
    $result = $conn->query($sql);

    if($result->num_rows == 1){
        // Login réussi
        $_SESSION['username'] = $username;
        header("Location: ../index.php"); // redirection vers le dashboard
        exit();
    } else {
        // Login échoué
        echo "<script>alert('Nom d’utilisateur ou mot de passe incorrect'); window.location='login.php';</script>";
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?> 

