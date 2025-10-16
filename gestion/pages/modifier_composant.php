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
$comp = $res->fetch_assoc();

if(isset($_POST['nom'], $_POST['description'], $_POST['prix'])){
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];

    $sql_update = "UPDATE Composant SET nom='$nom', description='$description', prix=$prix WHERE id_composant=$id";
    if($conn->query($sql_update) === TRUE){
        echo "<script>alert('Composant modifié'); window.location='Composant.php';</script>";
        exit();
    } else {
        echo "Erreur: ".$conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Composant - OCP</title>
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
            <!-- <div class="user-avatar"><?php echo strtoupper(substr($_SESSION['username'],0,1)); ?></div> -->
            <h2 >Modifier le Composant</h2>
        </div>

        <div class="table-container">
            <form action="" method="post" class="form-box">
                <div class="form-row">
                    <label>Nom</label>
                    <input type="text" name="nom" value="<?php echo $comp['nom']; ?>" required>
                </div>
                <div class="form-row">
                    <label>Description</label>
                    <input type="text" name="description" value="<?php echo $comp['description']; ?>" required>
                </div>
                <div class="form-row">
                    <label>Prix (DH)</label>
                    <input type="number" name="prix" step="0.01" value="<?php echo $comp['prix']; ?>" required>
                </div>
                <button type="submit" class="search-button">
                    <i class="fas fa-edit"></i> Modifier
                </button>
            </form>
        </div>
    </main>
</div>
</body>
</html>