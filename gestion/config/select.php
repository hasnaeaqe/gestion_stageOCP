<?php
include 'config.php';

$sql = "SELECT * FROM Technicien";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id_tech"]. " - Nom: " . $row["nom"]. " " . $row["prenom"]. "<br>";
    }
} else {
    echo "Aucun technicien trouvé";
}
?>
