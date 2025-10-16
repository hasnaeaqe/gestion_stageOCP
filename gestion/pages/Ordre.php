<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../views/login.php");
    exit();
}
include '../config/config.php';

// Récupérer tous les ordres avec technicien et équipement
$sql = "SELECT o.*, t.nom AS tech_nom, t.prenom AS tech_prenom, e.num_serie, e.type
        FROM Ordre o
        LEFT JOIN Technicien t ON o.id_tech=t.id_tech
        LEFT JOIN Equipement e ON o.id_equip=e.id_equip
        ORDER BY o.date_creation DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ordres - OCP</title>
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
        <a href="Ordre.php" class="nav-item active">
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
            <a href="ajouter_ordre.php" class="search-button">
                <i class="fas fa-plus"></i> Ajouter un Ordre
            </a>
        </div>
        <div class="welcome-section">
            <!-- <div class="user-avatar"><?php echo strtoupper(substr($_SESSION['username'],0,1)); ?></div> -->
            <h2>Liste des Ordres</h2>
        </div>


        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date création</th>
                        <th>Priorité</th>
                        <th>Type Intervention</th>
                        <th>Technicien</th>
                        <th>Équipement</th>
                        <th>Frais Composants</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): 
                            // Calcul frais totaux
                            $sql_composants = "SELECT SUM(c.prix) AS total
                                            FROM Ordre_Composant oc
                                            JOIN Composant c ON oc.id_composant=c.id_composant
                                            WHERE oc.id_ordre=".$row['id_ordre'];
                            $res_composants = $conn->query($sql_composants);
                            $total = $res_composants->fetch_assoc()['total'] ?? 0;
                        ?>
                            <tr>
                                <td><?php echo $row['id_ordre']; ?></td>
                                <td><?php echo $row['date_creation']; ?></td>
                                <td><?php echo $row['priorite']; ?></td>
                                <td><?php echo $row['type_intervention']; ?></td>
                                <td><?php echo $row['tech_nom'].' '.$row['tech_prenom']; ?></td>
                                <td><?php echo $row['num_serie'].' ('.$row['type'].')'; ?></td>
                                <td><?php echo number_format($total, 2).' DH'; ?></td>
                                <td class="actions">
                                    <a href="modifier_ordre.php?id=<?php echo $row['id_ordre']; ?>" class="btn-edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="supprimer_ordre.php?id=<?php echo $row['id_ordre']; ?>" class="btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet ordre?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="no-data">Aucun ordre trouvé</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
</body>
</html>





