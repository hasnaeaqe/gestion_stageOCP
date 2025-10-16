<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../views/login.php");
    exit();
}
include '../config/config.php';

$id = $_GET['id'] ?? 0;

$sql = "SELECT * FROM Composant WHERE id_composant=$id";
$res = $conn->query($sql);
if($res->num_rows != 1){
    header("Location: Composant.php");
    exit();
}
$composant = $res->fetch_assoc();

if(isset($_POST['confirm'])){
    // D'abord supprimer les liaisons avec les équipements et ordres
    $conn->query("DELETE FROM Equipement_Composant WHERE id_composant=$id");
    $conn->query("DELETE FROM Ordre_Composant WHERE id_composant=$id");
    
    // Puis supprimer le composant
    $sql_delete = "DELETE FROM Composant WHERE id_composant=$id";
    if($conn->query($sql_delete) === TRUE){
        echo "<script>alert('Composant supprimé'); window.location='Composant.php';</script>";
    } else {
        echo "Erreur: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Supprimer Composant - OCP</title>
    <link rel="stylesheet" href="../dash.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="container">
     <header>
            <div class="logo">
                <img src="logo_ocp.png" alt="logo" style="height: 50px; margin-right: 10px;">
                <h1>Maintenance Manager </h1>
                <!-- <i class="fas fa-cogs"></i> -->
            </div>
        </header>

    <!-- Sidebar -->
    <aside class="sidebar">
        <a href="../index.php" class="nav-item">
            <i class="fas fa-home"></i>
            <span>Accueil</span>
        </a>
        <a href="profil.php" class="nav-item">
                <i class="fas fa-user"></i>
                <span>Mon Profil</span>
            </a>
        <a href="Technicien.php" class="nav-item">
            <i class="fas fa-user-cog"></i>
            <span>Techniciens</span>
        </a>
        <a href="Equipement.php" class="nav-item">
            <i class="fas fa-tools"></i>
            <span>Équipements</span>
        </a>
        <a href="Ordre.php" class="nav-item">
            <i class="fas fa-clipboard-list"></i>
            <span>Ordres</span>
        </a>
        <a href="Composant.php" class="nav-item active">
            <i class="fas fa-cog"></i>
            <span>Composants</span>
        </a>
        <a href="../views/logout.php" class="nav-item logout">
            <i class="fas fa-sign-out-alt"></i>
            <span>Déconnexion</span>
        </a>
    </aside>

    <!-- Main content -->
    <main class="main-content">
        <div class="welcome-section" style="display: flex; justify-content: center; align-items: center; height: 100px;">
            <h2>Supprimer le Composant</h2>
        </div>
        <div class="table-container" >
            <p style="display: flex; justify-content: center; align-items: center; height: 100px;">Voulez-vous vraiment supprimer ce composant ?</p>
            <div class="form-box">
                <div class="form-row">
                    <label>ID :</label><span><?php echo $composant['id_composant']; ?></span>
                </div>
                <div class="form-row">
                    <label>Nom :</label><span><?php echo $composant['nom']; ?></span>
                </div>
                <div class="form-row">
                    <label>Description :</label><span><?php echo $composant['description']; ?></span>
                </div>
                <div class="form-row">
                    <label>Prix :</label><span><?php echo number_format($composant['prix'], 2) . ' DH'; ?></span>
                </div>
                <form method="post" style="margin-top:15px;">
                    <button type="submit" name="confirm" class="delete-button">
                        <i class="fas fa-trash-alt"></i> Supprimer
                    </button>
                    <a href="Composant.php" class="cancel-button">Annuler</a>
                </form>
            </div>
        </div>
    </main>
</div>
</body>
</html>