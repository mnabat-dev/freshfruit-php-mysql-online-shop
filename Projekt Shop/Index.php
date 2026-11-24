<?php
$servername = "localhost";
$username = "root";
$passwort = "";
$dbname = "mydb";
$port = 3306;

$conn = new mysqli($servername, $username, $passwort, $dbname, $port);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT status, iduser FROM user WHERE rolle = 'user'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$status = $row['status'];
$iduser = $row['iduser'];

if ($status != 'angemeldet') {
    header('Location: login.php');
    exit;
}

$sql = "Select Artikel_name, idArtikel, Artikel_preis, Artikel_bildpfad From artikel";
$result = mysqli_query($conn, $sql);

// Produkte aus der Datenbank laden
$produktname = array();
$idVomArtikel = array();
$preis = array();
$bildpfad = array();

while($row = mysqli_fetch_assoc($result)) {
    $produktname[] = $row['Artikel_name'];
    $idVomArtikel[] = $row['idArtikel'];
    $preis[] = $row['Artikel_preis'];
    $bildpfad[] = $row['Artikel_bildpfad'];
}
$produktanzahl = count($produktname);


$sql = "SELECT * FROM bestellung WHERE kategorieid = 1";
$result = mysqli_query($conn, $sql);

$warenkorbanzahl = 0;
while($row = mysqli_fetch_assoc($result)) {
    $warenkorbanzahl += $row['menge'];
}

if (isset($_POST['leeren'])) {
    mysqli_query($conn, "DELETE FROM bestellung WHERE kategorieid = 1");
    header('Location: Index.php');
    exit;

}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <title>Shop</title>
</head>
<body>
   <nav class="navbar">
       <a class="willkommen">Willkommen in Meinem Shop</a>  <a href="#home"> Startseite </a><a href="#produkte"> Produkte </a> <a href="#kontakt"> Kontakt</a> <a id="warenkorb-count" href="warenkorb.php"><?php echo $warenkorbanzahl ; ?> Warenkorb </a>
    </nav>

<main class="main-content">
    <h2 class="produkte" id="produkte" style="max-width: 900px; margin: 20px auto;">Unsere Produkte</h2>
    <div class="shop-container" >   
<?php
for ($i = 0; $i < $produktanzahl; $i++) {
    echo'<article class="produkt">
            <img src="' . $bildpfad[$i] . '" alt="' . $produktname[$i] .'">
            <div class="produkt-info">
                <h3>'. $produktname[$i] .'</h3>
                <p class="preis">'. $preis[$i] .' € / kg</p>     
                <button class="buy-btn" onclick="sendeDaten(\'' . $produktname[$i] . '\', \'' . $idVomArtikel[$i] . '\', \'' . $preis[$i] . '\', \'' . $bildpfad[$i] . '\')"> <span class="material-symbols-outlined">add_shopping_cart</span>In den Warenkorb</button>
            </div>
        </article>';
     

}
echo '</div>';
?>   

    
    <div class="warenkorb-actions" style="max-width: 900px; margin-right: auto; margin-left: auto; text-align: center;">
    <br><a href="warenkorb.php"><button class="btn"><span class="material-symbols-outlined">shopping_cart_checkout</span> Warenkorb ansehen</button></a>
    <form method="post" action="">
        <button type="submit" name="leeren" class="btn"><span class="material-symbols-outlined">delete</span> Warenkorb leeren</button>
    </form>
    </div>
    
</main>
    <footer class="footer" id="kontakt">
        <p>Autor: Mahmoud Nabat</p>
        <p><a href="mailto:nabat@gmail.com">nabat@gmail.com</a></p>
        <p><a href="login.php">Anmelden</a></p>
    </footer>

<script>
leerenWarenkorb = () => {
    if (confirm("Möchten Sie wirklich den Warenkorb leeren?")) {
        localStorage.removeItem("warenkorb");
        alert("Der Warenkorb wurde geleert.");
    }
};
const sendeDaten = (produktname, idArtikel, preis, bildpfad ) => {
    // 1. An den Server senden (für die Datenbank)
    let formData = new FormData();
    formData.append('name', produktname);
    formData.append('idArtikel', idArtikel);
    formData.append('preis', preis);
    formData.append('bildpfad', bildpfad);
    formData.append('userid', <?php echo $iduser; ?>);

    fetch('bestellung.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        alert(produktname + " wurde hinzugefügt!");
        location.reload();

    })
    .catch(error => console.error('Fehler:', error));
};


</script>
</body>
</html>