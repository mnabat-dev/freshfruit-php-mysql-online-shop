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

$sql = "UPDATE user SET status = 'abgemeldet' WHERE status = 'angemeldet'";
$result = mysqli_query($conn, $sql);



$sql = "SELECT Artikel_name, idArtikel, Artikel_preis, Artikel_beschreibung, Artikel_bildpfad FROM artikel";
$result = mysqli_query($conn, $sql);

// Produkte aus der Datenbank laden
$produktname = array();
$idVomArtikel = array();
$preis = array();
$beschreibung = array();
$bildpfad = array();

while($row = mysqli_fetch_assoc($result)) {
    $produktname[] = $row['Artikel_name'];
    $idVomArtikel[] = $row['idArtikel'];
    $preis[] = $row['Artikel_preis'];
    $beschreibung[] = $row['Artikel_beschreibung'];
    $bildpfad[] = $row['Artikel_bildpfad'];
}
$produktanzahl = count($produktname);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Startseite</title>
    <link rel="stylesheet" href="css.css">
    <style>
        .willkommen {
            font-size: 2.5em;
            margin-bottom: 20px;
        }
        .beschreibung {
            font-size: 1.2em;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1 class="willkommen" style="color: white;">Willkommen im Shop!</h1> 
    </nav>
    <main class="main-content">
        
        
            <h2 class="produkte" id="produkte" style="width: 1200px; margin: 20px auto;">Entdecken sie unsere Frichen und süßen Obstwaren.</h2>
    <div class="shop-container" >   
<?php
for ($i = 0; $i < $produktanzahl; $i++) {
    echo'<article class="produkt">
            <img src="' . $bildpfad[$i] . '" alt="' . $produktname[$i] .'">
            <div class="produkt-info">
                <h3>'. $produktname[$i] .'</h3>
                <p class="preis">'. $preis[$i] .' € / kg</p> 
                <p>'. $beschreibung[$i] .'</p>    
            </div>
        </article>';
     

}
echo '</div>';
?>
<div class="warenkorb-actions" style="max-width: 900px; margin-right: auto; margin-left: auto; text-align: center;">
    <br><a href="daten.php"><button class="btn"><span class="material-symbols-outlined">login</span> Zur Anmeldung</button></a>

    </div>
    </main>

<footer class="footer" id="kontakt">
        <p>Autor: Mahmoud Nabat</p>
        <p><a href="mailto:nabat@gmail.com">nabat@gmail.com</a></p>
        <p><a href="daten.php">Anmelden</a></p>
</footer>
</body>
</html>