<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../views/login.php");
    exit();
}
include '../config/config.php';

// Récupérer techniciens, équipements et composants
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

// Soumission du formulaire
if(isset($_POST['priorite'], $_POST['type_intervention'], $_POST['id_tech'], $_POST['id_equip'])){
    $priorite = $_POST['priorite'];
    $type_intervention = $_POST['type_intervention'];
    $id_tech = $_POST['id_tech'];
    $id_equip = $_POST['id_equip'];
    $selected_composants = $_POST['composants'] ?? [];

    // Ajouter l'ordre
    $sql = "INSERT INTO Ordre (priorite,type_intervention,id_tech,id_equip) 
            VALUES ('$priorite','$type_intervention',$id_tech,$id_equip)";
    if($conn->query($sql) === TRUE){
        $ordre_id = $conn->insert_id;
        // Ajouter les composants choisis
        foreach($selected_composants as $comp_id){
            $conn->query("INSERT INTO Ordre_Composant (id_ordre,id_composant) VALUES ($ordre_id,$comp_id)");
        }
        echo "<script>alert('Ordre ajouté avec succès'); window.location='Ordre.php';</script>";
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
    <title>Ajouter Ordre - OCP</title>
    <link rel="stylesheet" href="../dash.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .form-box select[multiple] { 
            height: 150px; 
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .total-frais { 
            font-weight:bold; 
            color:#43a047; 
            margin-top:10px; 
            font-size: 16px;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 4px;
        }
        .no-components {
            color: #999;
            font-style: italic;
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
        <a href="../index.php" class="nav-item"><i class="fas fa-home"></i> <span>Accueil</span></a>
        <a href="profil.php" class="nav-item">
                <i class="fas fa-user"></i>
                <span>Mon Profil</span>
            </a>
        <a href="Technicien.php" class="nav-item"><i class="fas fa-user-cog"></i> <span>Techniciens</span></a>
        <a href="Equipement.php" class="nav-item"><i class="fas fa-tools"></i> <span>Équipements</span></a>
        <a href="Ordre.php" class="nav-item active"><i class="fas fa-clipboard-list"></i> <span>Ordres</span></a>
        <a href="Composant.php" class="nav-item"><i class="fas fa-cogs"></i> <span>Composants</span></a>
        <a href="../views/logout.php" class="nav-item logout"><i class="fas fa-sign-out-alt"></i> <span>Déconnexion</span></a>
    </aside>

    <!-- Main content -->
    <main class="main-content">
        <div class="welcome-section" style="display: flex; justify-content: center; align-items: center; height: 100px;">
            <h2>Ajouter un Ordre</h2>
        </div>

        <div class="table-container">
            <form action="" method="post" class="form-box">
                <div class="form-row">
                    <label>Priorité</label>
                    <select name="priorite" required>
                        <option value="">Choisir</option>
                        <option value="Haute">Haute</option>
                        <option value="Moyenne">Moyenne</option>
                        <option value="Basse">Basse</option>
                    </select>
                </div>

                <div class="form-row">
                    <label>Type Intervention</label>
                    <input type="text" name="type_intervention" required>
                </div>

                <div class="form-row">
                    <label>Technicien</label>
                    <select name="id_tech" required>
                        <option value="">Choisir</option>
                        <?php while($t = $techs->fetch_assoc()): ?>
                            <option value="<?php echo $t['id_tech']; ?>"><?php echo $t['nom'].' '.$t['prenom']; ?></option>
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
                            <option value="<?php echo $e['id_equip']; ?>"><?php echo $e['num_serie'].' ('.$e['type'].')'; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-row">
                    <label>Composants à remplacer</label>
                    <select name="composants[]" multiple id="composantsSelect">
                        <option value="" class="no-components">Sélectionnez d'abord un équipement</option>
                    </select>
                </div>

                <div class="total-frais" id="totalFrais">Frais totaux: 0 DH</div>

                <button type="submit" class="search-button">
                    <i class="fas fa-plus"></i> Ajouter
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

// Filtrer composants selon équipement
equipSelect.addEventListener('change', ()=>{
    const equipId = equipSelect.value;
    
    compSelect.innerHTML = '';
    totalDiv.textContent = "Frais totaux: 0 DH";
    
    if(!equipId) {
        const option = document.createElement('option');
        option.value = '';
        option.textContent = 'Sélectionnez d\'abord un équipement';
        option.className = 'no-components';
        compSelect.appendChild(option);
        return;
    }
    
    const equipIdStr = equipId.toString();
    
    if(allComposants[equipIdStr] && allComposants[equipIdStr].length > 0) {
        allComposants[equipIdStr].forEach(c=>{
            const opt = document.createElement('option');
            opt.value = c.id_composant;
            opt.dataset.prix = c.prix;
            opt.textContent = c.nom + " (" + c.prix + " DH)";
            compSelect.appendChild(opt);
        });
    } else {
        const option = document.createElement('option');
        option.value = '';
        option.textContent = 'Aucun composant disponible pour cet équipement';
        option.className = 'no-components';
        compSelect.appendChild(option);
    }
});

// Calcul frais totaux
compSelect.addEventListener('change', ()=>{
    let total = 0;
    Array.from(compSelect.selectedOptions).forEach(opt=>{
        if(opt.dataset.prix) {
            total += parseFloat(opt.dataset.prix);
        }
    });
    totalDiv.textContent = "Frais totaux: " + total.toFixed(2) + " DH";
});

// Initialiser l'état au chargement
window.addEventListener('load', () => {
    if(equipSelect.value === '') {
        const option = document.createElement('option');
        option.value = '';
        option.textContent = 'Sélectionnez d\'abord un équipement';
        option.className = 'no-components';
        compSelect.appendChild(option);
    }
});
</script>
</body>
</html>
