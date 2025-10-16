<?php
// Démarrer la session en tout premier
session_start();

// Vérifier si l'utilisateur est connecté
if(!isset($_SESSION['username'])){
    header("Location: views/login.php");
    exit();
}

// Inclure la configuration de la base de données
include 'config/config.php';

// Vérifier si la connexion à la base de données est établie
if (!$conn) {
    die("Erreur de connexion à la base de données");
}

// Traitement recherche
$search = "";
$search_result = null;
if(isset($_GET['search']) && !empty($_GET['search'])){
    $search = $conn->real_escape_string($_GET['search']);

    // Requête multi-table harmonisée
    $sql_search = "
        SELECT 'Technicien' as type, id_tech as id, nom as col1, prenom as col2
        FROM Technicien 
        WHERE nom LIKE '%$search%' OR prenom LIKE '%$search%'
        
        UNION
        
        SELECT 'Équipement' as type, id_equip as id, type as col1, num_serie as col2
        FROM Equipement 
        WHERE type LIKE '%$search%' OR num_serie LIKE '%$search%'
        
        UNION 
        
        SELECT 'Ordre' as type, id_ordre as id, priorite as col1, type_intervention as col2
        FROM Ordre
        WHERE priorite LIKE '%$search%' OR type_intervention LIKE '%$search%'
    ";
    $search_result = $conn->query($sql_search);
}

// Récupérer les statistiques
$tech_count = 0;
$equip_count = 0;
$ordre_count = 0;

$result = $conn->query("SELECT COUNT(*) as total FROM Technicien");
if ($result) {
    $tech_count = $result->fetch_assoc()['total'];
}

$result = $conn->query("SELECT COUNT(*) as total FROM Equipement");
if ($result) {
    $equip_count = $result->fetch_assoc()['total'];
}

$result = $conn->query("SELECT COUNT(*) as total FROM Ordre");
if ($result) {
    $ordre_count = $result->fetch_assoc()['total'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - OCP Smart Maintenance</title>
    <link rel="stylesheet" href="dash.css">
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
            <a href="index.php" class="nav-item active">
                <i class="fas fa-home"></i>
                <span>Accueil</span>
            </a>
            <a href="pages/profil.php" class="nav-item">
                <i class="fas fa-user"></i>
                <span>Mon Profil</span>
            </a>
            <a href="pages/Technicien.php" class="nav-item">
                <i class="fas fa-user-cog"></i>
                <span>Techniciens</span>
            </a>
            <a href="pages/Equipement.php" class="nav-item">
                <i class="fas fa-tools"></i>
                <span>Équipements</span>
            </a>
            <a href="pages/Ordre.php" class="nav-item">
                <i class="fas fa-clipboard-list"></i>
                <span>Ordres</span>
            </a>
            <a href="views/logout.php" class="nav-item logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Déconnexion</span>
            </a>
        </aside>
        
        <main class="main-content">
            <!-- <div class="user-info"> -->
            <!-- <div class="welcome-section">
                <div class="user-avatar"><?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?></div>
                <h2>Bienvenue, <?php echo $_SESSION['username']; ?></h2>
                <br>
            </div> -->
            <div class="welcome-section">
                <div class="user-avatar"><?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?></div>
                <h2>Bienvenue, <?php echo $_SESSION['username']; ?></h2>
            </div>

            <div class="search-container">
                <form method="get" action="" class="search-form">
                    <input type="text" name="search" class="search-input" 
                           placeholder="Rechercher un technicien, équipement ou ordre..." 
                           value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="search-button">
                        <i class="fas fa-search"></i> Rechercher
                    </button>
                </form>
            </div>
            
            <?php if(!empty($search)) : ?>
            <div class="table-container">
                <div class="table-header">
                    <h3>Résultats de recherche pour "<?php echo htmlspecialchars($search); ?>"</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>ID</th>
                            <th>Info principale</th>
                            <th>Info secondaire</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if($search_result && $search_result->num_rows > 0){
                            while($row = $search_result->fetch_assoc()){
                                echo "<tr>
                                    <td>".$row['type']."</td>
                                    <td>".$row['id']."</td>
                                    <td>".$row['col1']."</td>
                                    <td>".$row['col2']."</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>Aucun résultat trouvé</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
            
            <div class="stats-container">
                <div class="stat-card">
                    <h3>TECHNICIENS</h3>
                    <div class="stat-value"><?php echo $tech_count; ?></div>
                    <div class="stat-icon">
                        <i class="fas fa-user-cog"></i>
                    </div>
                </div>
                <div class="stat-card">
                    <h3>ÉQUIPEMENTS</h3>
                    <div class="stat-value"><?php echo $equip_count; ?></div>
                    <div class="stat-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                </div>
                <div class="stat-card">
                    <h3>ORDRES</h3>
                    <div class="stat-value"><?php echo $ordre_count; ?></div>
                    <div class="stat-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                </div>
            </div>
            
            <div class="table-container">
                <div class="table-header">
                    <h3>Derniers Ordres</h3>
                    <a href="pages/Ordre.php" style="color: white; text-decoration: none;">
                        <i class="fas fa-external-link-alt"></i> Voir tous
                    </a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>ID Ordre</th>
                            <th>Technicien</th>
                            <th>Équipement</th>
                            <th>Priorité</th>
                            <th>Type Intervention</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT O.id_ordre, T.nom AS tech, E.type AS equip, O.priorite, O.type_intervention
                                FROM Ordre O
                                JOIN Technicien T ON O.id_tech = T.id_tech
                                JOIN Equipement E ON O.id_equip = E.id_equip
                                ORDER BY O.date_creation DESC
                                LIMIT 5";
                        $result = $conn->query($sql);
                        if($result && $result->num_rows > 0){
                            while($row = $result->fetch_assoc()){
                                echo "<tr>
                                    <td>".$row['id_ordre']."</td>
                                    <td>".$row['tech']."</td>
                                    <td>".$row['equip']."</td>
                                    <td>".$row['priorite']."</td>
                                    <td>".$row['type_intervention']."</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>Aucun ordre trouvé</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>