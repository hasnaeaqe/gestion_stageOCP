<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../views/login.php");
    exit();
}
include '../config/config.php';

if(isset($_POST['num_serie'], $_POST['type'])){
    $num_serie = $_POST['num_serie'];
    $type = $_POST['type'];

    // Vérifier si le numéro de série existe déjà
    $check_sql = "SELECT * FROM Equipement WHERE num_serie='$num_serie'";
    $check_result = $conn->query($check_sql);
    
    if($check_result->num_rows > 0){
        $error = "Ce numéro de série existe déjà !";
    } else {
        $sql_insert = "INSERT INTO Equipement (num_serie, type) VALUES ('$num_serie', '$type')";
        if($conn->query($sql_insert) === TRUE){
            echo "<script>alert('Équipement ajouté avec succès'); window.location='Equipement.php';</script>";
            exit();
        } else {
            $error = "Erreur: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter Équipement - OCP</title>
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
        <a href="Composant.php" class="nav-item">
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
            <h2>Ajouter un Équipement</h2>
        </div>

        <div class="table-container">
            <?php if(isset($error)): ?>
                <div class="error-message" style="color: red; margin-bottom: 15px; padding: 10px; background-color: #ffeeee; border: 1px solid red; border-radius: 4px;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="" method="post" class="form-box">
                <div class="form-row">
                    <label>Numéro de série *</label>
                    <input type="text" name="num_serie" required>
                </div>
                <div class="form-row">
                    <label>Type *</label>
                    <input type="text" name="type" required>
                </div>
                <button type="submit" class="search-button">
                    <i class="fas fa-plus"></i> Ajouter
                </button>
                <!-- <a href="Equipement.php" class="cancel-button" style="margin-left: 10px;">
                    <i class="fas fa-times"></i> Annuler
                </a> -->
            </form>
        </div>
    </main>
</div>
</body>
</html>