<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../views/login.php");
    exit();
}
include '../config/config.php';

$id = $_GET['id'] ?? 0;

$sql = "SELECT o.*, t.nom AS tech_nom, t.prenom AS tech_prenom, e.num_serie 
        FROM Ordre o
        LEFT JOIN Technicien t ON o.id_tech=t.id_tech
        LEFT JOIN Equipement e ON o.id_equip=e.id_equip
        WHERE o.id_ordre=$id";
$res = $conn->query($sql);
if($res->num_rows != 1){
    header("Location: Ordre.php");
    exit();
}
$order = $res->fetch_assoc();

if(isset($_POST['confirm'])){
    $sql_delete = "DELETE FROM Ordre WHERE id_ordre=$id";
    if($conn->query($sql_delete) === TRUE){
        echo "<script>alert('Ordre supprimé'); window.location='Ordre.php';</script>";
    } else {
        echo "Erreur: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Supprimer Ordre - OCP</title>
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

    <aside class="sidebar">
        <a href="../index.php" class="nav-item"><i class="fas fa-home"></i> Accueil</a>
        <a href="profil.php" class="nav-item">
                <i class="fas fa-user"></i>
                <span>Mon Profil</span>
            </a>
        <a href="Technicien.php" class="nav-item"><i class="fas fa-user-cog"></i> Techniciens</a>
        <a href="Equipement.php" class="nav-item"><i class="fas fa-tools"></i> Équipements</a>
        <a href="Ordre.php" class="nav-item active"><i class="fas fa-clipboard-list"></i> Ordres</a>
        <a href="Composant.php" class="nav-item"><i class="fas fa-cogs"></i> Composants</a>
        <a href="../views/logout.php" class="nav-item logout"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
    </aside>

    <main class="main-content">
        <div class="welcome-section" style="display: flex; justify-content: center; align-items: center; height: 100px;"><h2>Supprimer l'Ordre</h2></div>
        <div class="table-container">
            <p style="display: flex; justify-content: center; align-items: center; height: 100px;">Voulez-vous vraiment supprimer cet ordre ?</p>
            <div class="form-box">
                <div class="form-row"><label>ID :</label><span><?php echo $order['id_ordre']; ?></span></div>
                <div class="form-row"><label>Technicien :</label><span><?php echo $order['tech_nom'].' '.$order['tech_prenom']; ?></span></div>
                <div class="form-row"><label>Équipement :</label><span><?php echo $order['num_serie']; ?></span></div>
                <form method="post" style="margin-top:15px;">
                    <button type="submit" name="confirm" class="delete-button">
                        <i class="fas fa-trash-alt"></i> Supprimer
                    </button>
                    <a href="Ordre.php" class="cancel-button">Annuler</a>
                </form>
            </div>
        </div>
    </main>
</div>
</body>
</html>
