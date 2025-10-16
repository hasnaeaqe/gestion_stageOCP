<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../views/login.php");
    exit();
}
include '../config/config.php';

// Initialiser les variables pour conserver les valeurs en cas d'erreur
$nom = $description = $prix = '';

// Traitement du formulaire
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nom'], $_POST['description'], $_POST['prix'])){
    $nom = trim($_POST['nom']);
    $description = trim($_POST['description']);
    $prix = trim($_POST['prix']);
    
    // Validation des données
    $errors = [];
    
    if(empty($nom)) {
        $errors[] = "Le nom du composant est obligatoire";
    }
    
    if(empty($prix) || !is_numeric($prix) || $prix <= 0) {
        $errors[] = "Le prix doit être un nombre valide supérieur à 0";
    }
    
    // Si pas d'erreurs, insertion en base
    if(empty($errors)) {
        // Utilisation de requête préparée pour éviter les injections SQL
        $sql = "INSERT INTO Composant (nom, description, prix) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssd", $nom, $description, $prix);
        
        if($stmt->execute()) {
            $_SESSION['success'] = "Composant ajouté avec succès";
            header("Location: Composant.php");
            exit();
        } else {
            $errors[] = "Erreur lors de l'ajout: " . $conn->error;
        }
        $stmt->close();
    }
    
    // S'il y a des erreurs, les afficher
    if(!empty($errors)) {
        $error_message = implode("<br>", $errors);
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter Composant - OCP</title>
    <link rel="stylesheet" href="../dash.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .error-message {
            color: #e74c3c;
            background-color: #fadbd8;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .success-message {
            color: #27ae60;
            background-color: #d5f4e6;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .form-box {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-row {
            margin-bottom: 15px;
        }
        .form-row label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #2c3e50;
        }
        .form-row input, .form-row textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .form-row textarea {
            min-height: 100px;
            resize: vertical;
        }
    </style>
</head>
<body>
<div class="container">
     <header>
            <div class="logo">
                <img src="logo_ocp.png" alt="logo" style="height: 50px; margin-right: 10px;">
                <h1>Maintenance Manager </h1>
            </div>
        </header>

    <!-- Sidebar -->
    <aside class="sidebar">
        <a href="../index.php" class="nav-item"><i class="fas fa-home"></i> <span>Accueil</span></a>
        <a href="profil.php" class="nav-item">
                <i class="fas fa-user"></i>
                <span>Mon Profil</span>
            </a>
        <a href="Technicien.php" class="nav-item"><i class="fas fa-user-cog"></i> <span>Techniciens</span></a>
        <a href="Equipement.php" class="nav-item"><i class="fas fa-tools"></i> <span>Équipements</span></a>
        <a href="Ordre.php" class="nav-item"><i class="fas fa-clipboard-list"></i> <span>Ordres</span></a>
        <a href="Composant.php" class="nav-item active"><i class="fas fa-cogs"></i> <span>Composants</span></a>
        <a href="../views/logout.php" class="nav-item logout"><i class="fas fa-sign-out-alt"></i> <span>Déconnexion</span></a>
    </aside>

    <!-- Main content -->
    <main class="main-content">
        <div class="welcome-section">
            <h2>Ajouter un Composant</h2>
        </div>

        <div class="table-container">
            <!-- Messages d'alerte -->
            <?php if(isset($_SESSION['success'])): ?>
                <div class="success-message">
                    <?php 
                    echo $_SESSION['success']; 
                    unset($_SESSION['success']);
                    ?>
                </div>
            <?php endif; ?>
            
            <?php if(isset($error_message)): ?>
                <div class="error-message">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <form action="" method="post" class="form-box">
                <div class="form-row">
                    <label for="nom">Nom du composant *</label>
                    <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($nom); ?>" required>
                </div>
                
                <div class="form-row">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"><?php echo htmlspecialchars($description); ?></textarea>
                </div>
                
                <div class="form-row">
                    <label for="prix">Prix (DH) *</label>
                    <input type="number" id="prix" name="prix" step="0.01" min="0" value="<?php echo htmlspecialchars($prix); ?>" required>
                </div>
                
                <div class="form-row">
                    <button type="submit" class="search-button">
                        <i class="fas fa-plus"></i> Ajouter le composant
                    </button>
                    <!-- <a href="Composant.php" class="btn-cancel" style="display: inline-block; margin-left: 10px; padding: 10px 15px; background: #7f8c8d; color: white; text-decoration: none; border-radius: 4px;">
                        <i class="fas fa-arrow-left"></i> Annuler
                    </a> -->
                </div>
            </form>
        </div>
    </main>
</div>
</body>
</html>