<?php
$localhost = "localhost";
$username = "root";
$passwort = "";
$dbname = "mydb";
$port = 3306;

$conn = new mysqli($localhost, $username, $passwort, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT status, iduser, name FROM user WHERE rolle = 'user'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$status = $row['status'];
$iduser = $row['iduser'];
$name = $row['name'];

if ($status != 'angemeldet') {
    header('Location: login.php');
    exit;
}

$sql = "SELECT SUM(menge * preis) AS gesamtpreis FROM bestellung JOIN artikel ON bestellung.kategorieid = artikel.idArtikel WHERE bestellung.kategorieid = 1";
$result = mysqli_query($conn, $sql);

$row = mysqli_fetch_assoc($result);
$gesamtpreis = (float)($row['gesamtpreis'] ?? 0);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestellbestätigung</title>
    <link rel="stylesheet" href="css.css">

    <style>

        .text {
            width: 100%;
            text-align: center;
            color: #fff;
        }
        p {
            font-size: 1.2em;
            margin: 20px auto;
            width: 80%;
            text-align: center;
        }
    </style>

</head>
<body>
    <nav class="navbar">
        <h1 class="text">Bestellbestätigung</h1>
    </nav>
    <p>Vielen Dank <?php echo $name; ?>, für Ihre Bestellung! Wir werden Ihre Bestellung so schnell wie möglich bearbeiten.</p>
    <p>Der Gesamtpreis Ihrer Bestellung beträgt: <a class="preis"><?php echo number_format($gesamtpreis, 2); ?> €</a></p>
    <p>Sie erhalten in Kürze eine E-Mail mit weiteren Informationen zu Ihrer Bestellung und dem Versandstatus.<br>
        <big>Wir bitten sie die Offene Rechnung die wir ihnen per mail zu schicken so schnell wie möglich nach zu gehen.</big><br> Vielen Dank.<br>
        Bei Fragen oder Anliegen können Sie sich jederzeit an unseren Kundenservice wenden.</p>
    <p>
        <button onclick="window.location.href='Index.php'" class="btn" style="cursor: pointer;"> <span class="material-symbols-outlined">home</span> Zur Startseite</button>
    </p>

    <footer class="footer" id="kontakt">
        <p>Autor: Mahmoud Nabat</p>
        <p><a href="mailto:nabat@gmail.com">nabat@gmail.com</a></p>
    </footer>
</body>
</html>