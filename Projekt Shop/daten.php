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



$sql = "SELECT * FROM bestellung WHERE datum = CURDATE()";
$result = mysqli_query($conn, $sql);

$warenkorbanzahl = 0;
while($row = mysqli_fetch_assoc($result)) {
    $warenkorbanzahl += $row['menge'];
}



?>



<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <style>
        .option-card.selected {
            background-color: #e0e0e0 !important;
            border: 2px solid #006c2d;
        }
    </style>
</head>
<body>
   <nav class="navbar">
       <a href="warenkorb.php"><span class="material-symbols-outlined">arrow_back</span></a> <a class="willkommen">Kasse - Bestellung abschließen</a>  <a href="Index.php"> Startseite </a><a href="#produkte"> Produkte </a> <a href="#kontakt"> Kontakt</a>  <a id="warenkorb-count" href="warenkorb.php"><?php echo $warenkorbanzahl ; ?> Warenkorb </a>
    </nav>

<main class="main-content">
    <h2 class="produkte" style="max-width: 900px; margin: 20px auto;">Bestellinformationen</h2>
    <div class="shop-container" style="max-width: 900px; margin: 20px auto; text-align: center;">
        <p>Bitte geben Sie Ihre Informationen ein, um die Bestellung abzuschließen.</p>
        
        <form method="post" style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center;">
            <article class="produkt option-card" id="card-anmelden" style="cursor: pointer; position: relative;" onclick="selectOption('anmelden')">
                <div class="produkt-info" style="text-align: center; position: relative;">
                    <input type="radio" id="anmelden" name="option" value="anmelden" style="position: absolute; top: 10px; left: 10px;">
                    <span class="material-symbols-outlined" style="font-size: 48px; color: #006c2d;">account_circle</span>
                    <h3>Anmelden</h3>
                    <p>Melden Sie sich mit Ihrem bestehenden Konto an.</p>
                </div>
            </article>
            <article class="produkt option-card" id="card-registrieren" style="cursor: pointer; position: relative;" onclick="selectOption('registrieren')">
                <div class="produkt-info" style="text-align: center; position: relative;">
                    <input type="radio" id="registrieren" name="option" value="registrieren" style="position: absolute; top: 10px; left: 10px;">
                    <span class="material-symbols-outlined" style="font-size: 48px; color: #006c2d;">person_add</span>
                    <h3>Registrieren</h3>
                    <p>Erstellen Sie ein neues Konto.</p>
                </div>
            </article>
            <div style="width: 100%; text-align: center; margin-top: 20px;">
                <button type="submit" name="senden" class="btn">Bestellung abschicken</button>
            </div>
        </form>
    </div>
    <?php

if (isset($_POST['senden'])) {
    if (isset($_POST['option'])) {
        $option = $_POST['option'];
        if ($option == 'anmelden') {
            header('Location: login.php');
            exit;
        } elseif ($option == 'registrieren') {
            header('Location: /Projekt Shop/user/register.php');
            exit;
        }
    } else {
        echo "<p style='color: red;'>Keine Option ausgewählt.</p>";
    }
}
?>
</main>
    <footer class="footer" id="kontakt">
        <p>Autor: Mahmoud Nabat</p>
        <p><a href="mailto:nabat@gmail.com">nabat@gmail.com</a></p>
        <p><a href="login.php">Anmelden</a></p>
    </footer>

    <script>
        function selectOption(optionId) {
            // Radio-Button checken
            document.getElementById(optionId).checked = true;
            
            // Alle Karten deselektieren
            const cards = document.querySelectorAll('.option-card');
            cards.forEach(card => card.classList.remove('selected'));
            
            // Ausgewählte Karte selektieren
            const selectedCard = document.getElementById('card-' + optionId);
            selectedCard.classList.add('selected');
            
            // Button-Text anpassen
            const button = document.querySelector('.btn');
            if (optionId === 'anmelden') {
                button.textContent = 'Mit Konto anmelden';
                button.onclick = function() {
                    window.location.href = 'login.php';
                };
            } else if (optionId === 'registrieren') {
                button.textContent = 'Registrieren und bestellen';
                button.onclick = function() {
                    window.location.href = '/Projekt Shop/user/register.php';
                };
            }
        }
        // Optional: Button zurücksetzen, wenn keine Auswahl (z.B. bei Seitenladung)
        window.addEventListener('load', function() {
            const button = document.querySelector('.btn');
            if (!document.querySelector('input[name="option"]:checked')) {
                button.textContent = 'Bestellung abschicken';
            }
        });
    </script>
</body>
</html>