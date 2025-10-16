<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../views/login.php");
    exit();
}
include '../config/config.php';

// Récupérer les infos du technicien connecté
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT * FROM Technicien WHERE userName=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if(!$result || $result->num_rows != 1){
    echo "<p>Technicien introuvable</p>";
    exit();
}

$tech = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Mon Profil - OCP Smart Maintenance</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body { margin: 0; font-family: Arial, sans-serif; background: #f4f6f8; }
    /* Barre en haut verte */
    header {
        background: #43a047; /* vert OCP */
        color: #fff;
        height: 60px;
        display: flex;
        align-items: center;
        padding: 0 20px;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1000;
    }
    header img { height: 40px; margin-right: 10px; }
    header h1 { margin: 0; font-size: 20px; }

    /* Sidebar */
    .sidebar {
        position: fixed;
        top: 60px; /* juste en dessous de la barre du haut */
        left: 0;
        width: 220px;
        height: calc(100% - 60px);
        background: #2c3e50;
        padding-top: 20px;
        display: flex;
        flex-direction: column;
    }
    .sidebar a {
        color: #ecf0f1;
        text-decoration: none;
        padding: 12px 20px;
        display: flex;
        align-items: center;
        margin-bottom: 5px;
        transition: background 0.2s;
    }
    .sidebar a i { margin-right: 10px; }
    .sidebar a:hover, .sidebar a.active { background: #43a047; color: #fff; }

    /* Contenu principal */
    .main-content {
        margin-left: 240px; /* espace pour le sidebar */
        padding: 100px 40px 40px; /* espace pour la barre en haut */
    }

    .profile-card {
        max-width: 700px;
        margin: 0 auto;
        padding: 25px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .profile-header {
        text-align: center;
        margin-bottom: 20px;
    }
    .profile-header img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        margin-bottom: 10px;
    }
    .profile-header h2 { margin: 10px 0 5px; color: #2c3e50; }
    .profile-header p { margin: 0; color: #7f8c8d; }

    .info-group {
        background: #f9f9f9;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
    }
    .info-group i { font-size: 20px; margin-right: 15px; color: #43a047; }
    .info-details { flex: 1; }
    .info-details label { display: block; font-size: 14px; color: #7f8c8d; margin-bottom: 3px; }
    .info-details span { font-size: 16px; font-weight: bold; color: #2c3e50; }

    .btns { text-align: center; margin-top: 20px; }
    .btns a {
        display: inline-block;
        padding: 12px 20px;
        border-radius: 6px;
        color: #fff;
        text-decoration: none;
        margin: 0 10px;
    }
    .btn-update { background: #43a047; }
    .btn-logout { background: #d32f2f; }

    @media (max-width: 768px) {
        .sidebar { width: 60px; }
        .sidebar a span { display: none; }
        .main-content { margin-left: 70px; padding: 100px 20px 20px; }
    }
</style>
</head>
<body>

<header>
    <img src="logo_ocp.png" alt="logo">
    <h1>Maintenance Manager</h1>
</header>

<aside class="sidebar">
    <a href="../index.php"><i class="fas fa-home"></i><span>Accueil</span></a>
    <a href="profil.php" class="active"><i class="fas fa-user"></i><span>Mon Profil</span></a>
    <a href="Technicien.php"><i class="fas fa-user-cog"></i><span>Techniciens</span></a>
    <a href="Equipement.php"><i class="fas fa-tools"></i><span>Équipements</span></a>
    <a href="Ordre.php"><i class="fas fa-clipboard-list"></i><span>Ordres</span></a>
    <a href="../views/logout.php"><i class="fas fa-sign-out-alt"></i><span>Déconnexion</span></a>
</aside>

<main class="main-content">
    <div class="profile-card">
        <div class="profile-header">
            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="avatar">
            <h2><?php echo htmlspecialchars($tech['prenom']." ".$tech['nom']); ?></h2>
            <p><?php echo htmlspecialchars($tech['fonction']); ?></p>
        </div>

        <div class="info-group">
            <i class="fas fa-id-card"></i>
            <div class="info-details">
                <label>Nom complet</label>
                <span><?php echo htmlspecialchars($tech['prenom']." ".$tech['nom']); ?></span>
            </div>
        </div>
        <div class="info-group">
            <i class="fas fa-user"></i>
            <div class="info-details">
                <label>Nom d'utilisateur</label>
                <span><?php echo htmlspecialchars($tech['userName']); ?></span>
            </div>
        </div>
        <div class="info-group">
            <i class="fas fa-briefcase"></i>
            <div class="info-details">
                <label>Fonction</label>
                <span><?php echo htmlspecialchars($tech['fonction']); ?></span>
            </div>
        </div>

        <div class="btns">
            <a href="edit_profil.php" class="btn-update"><i class="fas fa-edit"></i> Modifier mes infos</a>
            <a href="../views/logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Se déconnecter</a>
        </div>
    </div>
</main>

</body>
</html>
