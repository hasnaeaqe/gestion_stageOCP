<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../views/login.php");
    exit();
}
include '../config/config.php';

// Récupérer techniciens et équipements
$techs = $conn->query("SELECT * FROM Technicien");
$equips = $conn->query("SELECT * FROM Equipement");

// Charger tous les composants par équipement
$compoEquip = [];
$res = $conn->query("SELECT ec.id_equip, c.id_composant, c.nom, c.prix 
                     FROM Equipement_Composant ec
                     JOIN Composant c ON ec.id_composant=c.id_composant");
while($row = $res->fetch_assoc()){
    $compoEquip[$row['id_equip']][] = [
        'id_composant'=>$row['id_composant'],
        'nom'=>$row['nom'],
        'prix'=>$row['prix']
    ];
}

if(!isset($_GET['id'])){
    header("Location: Ordre.php");
    exit();
}

$id_ordre = intval($_GET['id']);

// Récupérer les données de l'ordre
$ordre_res = $conn->query("SELECT * FROM Ordre WHERE id_ordre=$id_ordre");
$ordre = $ordre_res->fetch_assoc();

// Récupérer les composants sélectionnés
$selected_composants = [];
$sel_res = $conn->query("SELECT id_composant FROM Ordre_Composant WHERE id_ordre=$id_ordre");
while($row = $sel_res->fetch_assoc()){
    $selected_composants[] = $row['id_composant'];
}

// Soumission formulaire
if(isset($_POST['priorite'], $_POST['type_intervention'], $_POST['id_tech'], $_POST['id_equip'])){
    $priorite = $_POST['priorite'];
    $type_intervention = $_POST['type_intervention'];
    $id_tech = $_POST['id_tech'];
    $id_equip = $_POST['id_equip'];
    $selected_composants = $_POST['composants'] ?? [];

    // Mettre à jour l'ordre
    $conn->query("UPDATE Ordre SET priorite='$priorite', type_intervention='$type_intervention', id_tech=$id_tech, id_equip=$id_equip WHERE id_ordre=$id_ordre");

    // Supprimer anciens composants
    $conn->query("DELETE FROM Ordre_Composant WHERE id_ordre=$id_ordre");

    // Ajouter les nouveaux composants
    foreach($selected_composants as $comp_id){
        $conn->query("INSERT INTO Ordre_Composant (id_ordre,id_composant) VALUES ($id_ordre,$comp_id)");
    }

    echo "<script>alert('Ordre modifié avec succès'); window.location='Ordre.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Ordre - OCP</title>
    <link rel="stylesheet" href="../dash.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .form-box select[multiple] { 
            height: 150px; 
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
        }
        .total-frais { 
            font-weight: bold; 
            color: #43a047; 
            margin-top: 15px;
            padding: 10px;
            background-color: #f0f9f0;
            border-radius: 4px;
            text-align: center;
        }
        .form-row {
            margin-bottom: 15px;
        }
    </style>
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
        <div class="welcome-section" style="display: flex; justify-content: center; align-items: center; height: 100px;">
            <!-- <div class="user-avatar"><?php echo strtoupper(substr($_SESSION['username'],0,1)); ?></div> -->
            <h2>Modifier l'Ordre</h2>
        </div>

        <div class="table-container">
            <form action="" method="post" class="form-box">
                <div class="form-row">
                    <label>Priorité</label>
                    <select name="priorite" required>
                        <option value="">Choisir</option>
                        <option value="Haute" <?php if($ordre['priorite']=='Haute') echo 'selected'; ?>>Haute</option>
                        <option value="Moyenne" <?php if($ordre['priorite']=='Moyenne') echo 'selected'; ?>>Moyenne</option>
                        <option value="Basse" <?php if($ordre['priorite']=='Basse') echo 'selected'; ?>>Basse</option>
                    </select>
                </div>

                <div class="form-row">
                    <label>Type Intervention</label>
                    <input type="text" name="type_intervention" value="<?php echo $ordre['type_intervention']; ?>" required>
                </div>

                <div class="form-row">
                    <label>Technicien</label>
                    <select name="id_tech" required>
                        <option value="">Choisir</option>
                        <?php while($t = $techs->fetch_assoc()): ?>
                            <option value="<?php echo $t['id_tech']; ?>" <?php if($t['id_tech']==$ordre['id_tech']) echo 'selected'; ?>>
                                <?php echo $t['nom'].' '.$t['prenom']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-row">
                    <label>Équipement</label>
                    <select name="id_equip" id="equipementSelect" required>
                        <option value="">Choisir</option>
                        <?php 
                        $equips->data_seek(0);
                        while($e = $equips->fetch_assoc()): ?>
                            <option value="<?php echo $e['id_equip']; ?>" <?php if($e['id_equip']==$ordre['id_equip']) echo 'selected'; ?>>
                                <?php echo $e['num_serie'].' ('.$e['type'].')'; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-row">
                    <label>Composants à remplacer</label>
                    <select name="composants[]" multiple id="composantsSelect">
                        <!-- Options remplies dynamiquement -->
                    </select>
                </div>

                <div class="total-frais" id="totalFrais">Frais totaux: 0 DH</div>

                <button type="submit" class="search-button">
                    <i class="fas fa-edit"></i> Modifier l'Ordre
                </button>
            </form>
        </div>
    </main>
</div>

<script>
const allComposants = <?php echo json_encode($compoEquip); ?>;
const equipSelect = document.getElementById('equipementSelect');
const compSelect = document.getElementById('composantsSelect');
const totalDiv = document.getElementById('totalFrais');
const selectedComposants = <?php echo json_encode($selected_composants); ?>;

function remplirComposants(){
    const equipId = equipSelect.value.toString();
    compSelect.innerHTML = '';
    totalDiv.textContent = "Frais totaux: 0 DH";
    if(equipId && allComposants[equipId]){
        allComposants[equipId].forEach(c=>{
            const opt = document.createElement('option');
            opt.value = c.id_composant;
            opt.dataset.prix = c.prix;
            opt.textContent = c.nom + " (" + c.prix + " DH)";
            if(selectedComposants.includes(c.id_composant)){
                opt.selected = true;
            }
            compSelect.appendChild(opt);
        });
    }
    calculTotal();
}

// Calcul frais totaux
function calculTotal(){
    let total = 0;
    Array.from(compSelect.selectedOptions).forEach(opt=>{
        total += parseFloat(opt.dataset.prix);
    });
    totalDiv.textContent = "Frais totaux: " + total.toFixed(2) + " DH";
}

equipSelect.addEventListener('change', remplirComposants);
compSelect.addEventListener('change', calculTotal);

// Charger composants à l'ouverture
window.onload = remplirComposants;
</script>
</body>
</html>