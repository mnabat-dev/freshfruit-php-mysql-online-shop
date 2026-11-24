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


$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $telefonnummer = trim($_POST['telefonnummer'] ?? '');
    $plz = trim($_POST['plz'] ?? '');
    $ort = trim($_POST['ort'] ?? '');
    $strasse = trim($_POST['strasse'] ?? '');
    $hausnummer = trim($_POST['hausnummer'] ?? '');


    // Einfache Validierung
    if ($password !== $password_confirm) {
        $error = "Die Passwörter stimmen nicht überein.";
    } elseif ($username === '' || $email === '' || $password === '') {
        $error = "Alle Felder müssen ausgefüllt werden.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Bitte geben Sie eine gültige E-Mail-Adresse ein.";
    } else {

        $sql = "SELECT iduser FROM user WHERE name = '".   mysqli_real_escape_string($conn, $username) ."' OR email = '". mysqli_real_escape_string($conn, $email)."'";
        $result = mysqli_query($conn, $sql);

        $emaildb = array();
        while($row = mysqli_fetch_assoc($result)) {
           $emaildb[] = $row['email'];
        }

        if (in_array($email, $emaildb)) {
            $error = "Ein Benutzer mit diesem Namen oder dieser E-Mail existiert bereits.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO user (name, Telefonnummer, email, passwort, PLZ, Ort, Strasse, Hausnummer, rolle) VALUES ('".   mysqli_real_escape_string($conn, $username) ."', '".   mysqli_real_escape_string($conn, $telefonnummer) ."', '".   mysqli_real_escape_string($conn, $email) ."', '".   mysqli_real_escape_string($conn, $hashed_password) ."', '".   mysqli_real_escape_string($conn, $plz) ."', '".   mysqli_real_escape_string($conn, $ort) ."', '".   mysqli_real_escape_string($conn, $strasse) ."', '".   mysqli_real_escape_string($conn, $hausnummer) ."', 'user')";
            if (mysqli_query($conn, $sql)) {
                header('Location: ../login.php');
                exit;
            }

            $error = "Fehler bei der Registrierung: " . $insert->error;
        }
    }
}
?>



<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <title>Registrieren</title>
    <style>
        .form-container {
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .form-container input {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #006c2d;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #00411b;
        }
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 90%;
        }
    </style>
</head>
<body>
   <nav class="navbar">
        <a href="../daten.php"><span class="material-symbols-outlined">arrow_back</span></a> <a class="willkommen">Registrieren - Shop</a> <a href="../Index.php"> Startseite </a><a href="../Index.php#produkte"> Produkte </a> <a href="../Index.php#kontakt"> Kontakt</a> 
    </nav>

<main class="main-content">
    <h2 class="produkte" style="max-width: 900px; margin: 20px auto;">Registrieren</h2>
    <div class="form-container">
        <?php if ($error !== '') : ?>
            <div style="color: #a33; margin-bottom: 1rem;"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

    <table>
        <caption>Erstellen Sie ein neues Konto, um Ihre Bestellung zu verwalten.<br><h3>Kontaktdaten:</h3></caption>
        <thead>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <tr>    
                <th><label for="username">Name:</label></th>
                <td><input style="width: 90%;" type="text" id="username" name="username" required></td>
            </tr>
            <tr>
                <th><label for="telefonnummer">Telefonnummer:</label></th>
                <td><input style="width: 90%;" type="number_format" id="telefonnummer" name="telefonnummer" required></td>
            </tr>
            <tr>
                <th><label for="email">E-Mail:</label></th>
                <td><input style="width: 90%;" type="email" id="email" name="email" required></td>
            </tr>
        </thead>
    </table>
        <table>
        <caption><h3>Adresse :</h3></caption>
        <thead>
          
            <tr>
                <th><label for="plz">PLZ:</label></th>
                <td><input style="width: 90%;" type="number_format" id="plz" name="plz" required></td>
            </tr> 
            <tr>
                <th><label for="ort">Ort:</label></th>
                <td><input style="width: 90%;" type="text" id="ort" name="ort" required></td>
            </tr>
            <tr>
                <th><label for="strasse">Straße:</label></th>
                <td><input style="width: 90%;" type="text" id="strasse" name="strasse" required></td>
            </tr>
            <tr>
                <th><label for="hausnummer">Hausnummer:</label></th>
                <td><input style="width: 90%;" type="text" id="hausnummer" name="hausnummer" required></td>
            </tr>

        </thead>
    </table>
     <table>
        <caption><h3>Passwort:</h3></caption>
        <thead>
            <tr>
                <th><label for="password">Passwort Eingeben:</label></th>
                <td><input style="width: 90%;" type="password" id="password" name="password" required></td>
            </tr>
             <tr>
                <th><label for="password">Passwort bestätigen:</label></th>
                <td><input style="width: 90%;" type="password" id="password_confirm" name="password_confirm" required></td>
            </tr>
            </tr>
                <td colspan="2"><button type="submit">Registrieren</button></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center; padding-top: 20px;"><a href="../login.php">Bereits ein Konto? Anmelden</a></td>
            </tr>
            </form>
        </thead>
    </table>
        
    </div>
</main>
    <footer class="footer" id="kontakt">
        <p>Autor: Mahmoud Nabat</p>
        <p><a href="mailto:nabat@gmail.com">nabat@gmail.com</a></p>
    </footer>
</body>
</html>
