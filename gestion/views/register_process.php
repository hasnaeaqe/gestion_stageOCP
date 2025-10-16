<?php
session_start();
include '../config/config.php';

// Vérifier si le formulaire a été soumis
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Nettoyer et valider les données
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $fonction = trim($_POST['fonction']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Sauvegarder les données du formulaire en cas d'erreur
    $_SESSION['form_data'] = [
        'nom' => $nom,
        'prenom' => $prenom,
        'fonction' => $fonction,
        'username' => $username
    ];

    // Validation des données
    $errors = [];

    // Vérifier que tous les champs sont remplis
    if(empty($nom) || empty($prenom) || empty($fonction) || empty($username) || empty($password)) {
        $errors[] = "Tous les champs sont obligatoires";
    }

    // Vérifier la longueur du mot de passe
    if(strlen($password) < 6) {
        $errors[] = "Le mot de passe doit contenir au moins 6 caractères";
    }

    // Vérifier que les mots de passe correspondent
    if($password !== $confirm_password) {
        $errors[] = "Les mots de passe ne correspondent pas";
    }

    // Vérifier le format du nom d'utilisateur
    if(!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors[] = "Le nom d'utilisateur ne peut contenir que des lettres, chiffres et underscores";
    }

    // Si il y a des erreurs, rediriger vers le formulaire
    if(!empty($errors)) {
        $_SESSION['error'] = implode('<br>', $errors);
        header("Location: register.php");
        exit();
    }

    // Vérifier si le nom d'utilisateur existe déjà (avec requête préparée)
    $check_query = "SELECT id_tech FROM Technicien WHERE userName = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0) {
        $_SESSION['error'] = "Ce nom d'utilisateur est déjà pris";
        header("Location: register.php");
        exit();
    }
    $stmt->close();

    // Hacher le mot de passe (IMPORTANT: ne jamais stocker en clair)
    $hashed_password = $password;

    // Insérer le nouvel utilisateur (avec requête préparée)
    $insert_query = "INSERT INTO Technicien (nom, prenom, fonction, userName, password) 
                     VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("sssss", $nom, $prenom, $fonction, $username, $hashed_password);

    if($stmt->execute()) {
        // Succès - nettoyer les données de session et rediriger
        if(isset($_SESSION['form_data'])) {
            unset($_SESSION['form_data']);
        }
        if(isset($_SESSION['error'])) {
            unset($_SESSION['error']);
        }
        
        $_SESSION['success'] = "Compte créé avec succès. Vous pouvez maintenant vous connecter.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['error'] = "Erreur lors de la création du compte: " . $conn->error;
        header("Location: register.php");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    // Si la page est accédée directement sans formulaire
    header("Location: register.php");
    exit();
}
?>