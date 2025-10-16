<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../login.php");
    exit();
}
include '../config/config.php';

$id = $_GET['id'] ?? 0;
$sql = "SELECT * FROM Equipement WHERE id_equip=$id";
$res = $conn->query($sql);
if($res->num_rows != 1){
    header("Location: Equipement.php");
    exit();
}
$equip = $res->fetch_assoc();

if(isset($_POST['num_serie'], $_POST['type'])){
    $num_serie = $_POST['num_serie'];
    $type = $_POST['type'];

    $sql_update = "UPDATE Equipement SET num_serie='$num_serie', type='$type' WHERE id_equip=$id";
    if($conn->query($sql_update) === TRUE){
        echo "<script>alert('Équipement modifié avec succès'); window.location='Equipement.php';</script>";
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
    <title>Modifier Équipement - OCP</title>
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
        <a href="Equipement.php" class="nav-item active">
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
            <h2>Modifier l'Équipement</h2>
        </div>

        <div class="table-container">
            <form action="" method="post" class="form-box">
                <div class="form-row">
                    <label>Numéro de série</label>
                    <input type="text" name="num_serie" value="<?php echo $equip['num_serie']; ?>" required>
                </div>
                <div class="form-row">
                    <label>Type</label>
                    <input type="text" name="type" value="<?php echo $equip['type']; ?>" required>
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