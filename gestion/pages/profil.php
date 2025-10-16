<?php 
session_start(); 

if(!isset($_SESSION['username'])) { 
    header("Location: ../views/login.php"); 
    exit(); 
} 

include '../config/config.php'; 

// Récupérer les informations du technicien connecté
$username = $_SESSION['username'];
$sql = "SELECT * FROM Technicien WHERE userName = '$username'";
$result = $conn->query($sql);

if($result && $result->num_rows > 0) {
    $technicien = $result->fetch_assoc();
} else {
    // Rediriger si le technicien n'est pas trouvé
    header("Location: ../views/login.php"); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil Technicien - OCP Smart Maintenance</title>
    <link rel="stylesheet" href="../dash.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .profile-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            /* background-color: #fff; 
            border-radius: 8px;
            box-shadow: 0 20px 20px rgba(0, 0, 0, 0.1); */
        }
        /* .profile-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        } */
        
        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .profile-avatar {
            width: 100px;
            height: 100px;
            background-color: #2c3e50;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 40px;
            font-weight: bold;
            margin: 0 auto 15px;
        }
        
        .profile-name {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .profile-function {
            color: #7f8c8d;
            font-size: 18px;
        }
        
        .info-group {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        
        .info-group i {
            font-size: 20px;
            margin-right: 15px;
            color: #43a047;
            width: 30px;
            text-align: center;
        }
        
        .info-details {
            flex: 1;
        }
        
        .info-label {
            display: block;
            font-size: 14px;
            color: #7f8c8d;
            margin-bottom: 3px;
        }
        
        .info-value {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .profile-actions {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
        }
        
        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .btn-edit {
            background-color: #43a047;
            color: white;
        }
        
        .btn-logout {
            background-color: #e74c3c;
            color: white;
        }
        
        .btn i {
            margin-right: 10px;
        }
        
        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .profile-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
        .btn-delete {
    background-color: #e74c3c;
    color: white;
}

        
    </style>
</head>
<body>
<div class="container">

   <header>
        <div class="logo">
            <img src="logo_ocp.png" alt="logo" style="height: 50px; margin-right: 10px;">
            <h1>Maintenance Manager</h1>
        </div>
    </header>

    <!-- Sidebar -->
    <div class="main-layout">
        <aside class="sidebar">
            <a href="../index.php" class="nav-item">
                <i class="fas fa-home"></i>
                <span>Accueil</span>
            </a>
            <a href="profil.php" class="nav-item active">
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
            <a href="../views/logout.php" class="nav-item logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Déconnexion</span>
            </a>
        </aside>
    </div>

    <!-- Main content -->
    <main class="main-content">
        <div class="welcome-section">
                <div class="user-avatar"><?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?></div>
                <h2>Bienvenue, <?php echo $_SESSION['username']; ?></h2>
            </div>
        <div class="profile-container">
            <div class="profile-header">
                <div class="profile-avatar">
                    <?php echo strtoupper(substr($technicien['prenom'], 0, 1) . substr($technicien['nom'], 0, 1)); ?>
                </div>
                <br>
                <!-- <div class="profile-name"><?php echo $technicien['prenom'] . ' ' . $technicien['nom']; ?></div> -->
                <!-- <div class="profile-function"><?php echo $technicien['fonction']; ?></div> -->
            </div>
            
            <div class="profile-info">
                <div class="info-group">
                    <i class="fas fa-id-card"></i>
                    <div class="info-details">
                        <div class="info-label">Nom complet</div>
                        <div class="info-value"><?php echo $technicien['prenom'] . ' ' . $technicien['nom']; ?></div>
                    </div>
                </div>
                
                <div class="info-group">
                    <i class="fas fa-user"></i>
                    <div class="info-details">
                        <div class="info-label">Nom d'utilisateur</div>
                        <div class="info-value"><?php echo $technicien['userName']; ?></div>
                    </div>
                </div>
                
                <div class="info-group">
                    <i class="fas fa-briefcase"></i>
                    <div class="info-details">
                        <div class="info-label">Fonction</div>
                        <div class="info-value"><?php echo $technicien['fonction']; ?></div>
                    </div>
                </div>
                
                <!-- <?php if(isset($technicien['id_tech'])): ?>
                <div class="info-group">
                    <i class="fas fa-id-badge"></i>
                    <div class="info-details">
                        <div class="info-label">ID Technicien</div>
                        <div class="info-value"><?php echo $technicien['id_tech']; ?></div>
                    </div>
                </div>
                <?php endif; ?>
                 -->
                <?php if(isset($technicien['email'])): ?>
                <div class="info-group">
                    <i class="fas fa-envelope"></i>
                    <div class="info-details">
                        <div class="info-label">Email</div>
                        <div class="info-value"><?php echo $technicien['email']; ?></div>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if(isset($technicien['telephone'])): ?>
                <div class="info-group">
                    <i class="fas fa-phone"></i>
                    <div class="info-details">
                        <div class="info-label">Téléphone</div>
                        <div class="info-value"><?php echo $technicien['telephone']; ?></div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="profile-actions">
                <a href="modifier_technicien.php?id=<?php echo $technicien['id_tech']; ?>" class="btn btn-edit">
                    <i class="fas fa-edit"></i> Modifier mes infos
                </a>
               <!-- <a href="supprimer_technicien.php" class="btn btn-delete">
                    <i class="fas fa-trash-alt"></i> Supprimer le Compte
                </a> -->
                <a href="../views/logout.php" class="btn btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Se déconnecter
                </a>
            </div>
        </div>
    </main>

</div>
</body>
</html>