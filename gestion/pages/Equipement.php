<?php 
session_start(); 
if(!isset($_SESSION['username'])) { 
    header("Location: ../views/login.php"); 
    exit(); 
} 
include '../config/config.php'; 

// Récupérer tous les équipements
$sql = "SELECT * FROM Equipement"; 
$result = $conn->query($sql); 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Équipements - OCP Smart Maintenance</title>
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
        <div class="add-btn" style="margin-bottom: 20px;">
            <a href="ajouter_equipement.php" class="search-button">
                <i class="fas fa-plus"></i> Ajouter un Équipement
            </a>
        </div>
        <div class="welcome-section">
            <!-- <div class="user-avatar"><?php echo strtoupper(substr($_SESSION['username'],0,1)); ?></div> -->
            <h2>Liste des Équipements</h2>
        </div>


        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Numéro de série</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if($result && $result->num_rows > 0) { 
                        while($row = $result->fetch_assoc()) { 
                            echo "<tr>";
                            echo "<td>".$row['id_equip']."</td>";
                            echo "<td>".$row['num_serie']."</td>";
                            echo "<td>".$row['type']."</td>";
                            echo "<td class='actions'>";
                            echo "<a href='modifier_equipement.php?id=".$row['id_equip']."' class='btn-edit'>";
                            echo "<i class='fas fa-edit'></i>";
                            echo "</a>";
                            echo "<a href='supprimer_equipement.php?id=".$row['id_equip']."' class='btn-delete' onclick=\"return confirm('Êtes-vous sûr de vouloir supprimer cet équipement?')\">";
                            echo "<i class='fas fa-trash'></i>";
                            echo "</a>";
                            echo "</td>";
                            echo "</tr>";
                        } 
                    } else { 
                        echo "<tr><td colspan='4' class='no-data'>Aucun équipement trouvé</td></tr>"; 
                    } 
                    ?>
                </tbody>
            </table>
        </div>
    </main>

</div>
</body>
</html>