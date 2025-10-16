<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../views/login.php");
    exit();
}
include '../config/config.php';

$id = $_GET['id'] ?? 0;

$sql = "SELECT * FROM Technicien WHERE id_tech=$id";
$res = $conn->query($sql);
if($res->num_rows != 1){
    header("Location: Technicien.php");
    exit();
}
$tech = $res->fetch_assoc();

if(isset($_POST['nom'], $_POST['prenom'], $_POST['fonction'], $_POST['username'], $_POST['password'])){
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $fonction = $_POST['fonction'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql_update = "UPDATE Technicien 
                   SET nom='$nom', prenom='$prenom', fonction='$fonction', userName='$username', password='$password'
                   WHERE id_tech=$id";
    if($conn->query($sql_update) === TRUE){
        echo "<script>alert('Technicien modifié avec succès'); window.location='Technicien.php';</script>";
        exit();
    } else {
        echo "Erreur: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Technicien - OCP</title>
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
        <a href="Technicien.php" class="nav-item active">
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
        <a href="../views/logout.php" class="nav-item logout">
            <i class="fas fa-sign-out-alt"></i>
            <span>Déconnexion</span>
        </a>
    </aside>

    <!-- Main content -->
    <main class="main-content">
        <div class="welcome-section" style="display: flex; justify-content: center; align-items: center; height: 100px;">
            <!-- <div class="user-avatar"><?php echo strtoupper(substr($_SESSION['username'],0,1)); ?></div> -->
            <h2>Modifier Mes infos</h2>
        </div>

        <div class="table-container">
            <form action="" method="post" class="form-box">
                <div class="form-row">
                    <label>Nom</label>
                    <input type="text" name="nom" value="<?php echo $tech['nom']; ?>" required>
                </div>
                <div class="form-row">
                    <label>Prénom</label>
                    <input type="text" name="prenom" value="<?php echo $tech['prenom']; ?>" required>
                </div>
                <div class="form-row">
                    <label>Fonction</label>
                    <input type="text" name="fonction" value="<?php echo $tech['fonction']; ?>" required>
                </div>
                <div class="form-row">
                    <label>Nom d'utilisateur</label>
                    <input type="text" name="username" value="<?php echo $tech['userName']; ?>" required>
                </div>
                <div class="form-row">
                    <label>Mot de passe</label>
                    <input type="text" name="password" value="<?php echo $tech['password']; ?>" required>
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
