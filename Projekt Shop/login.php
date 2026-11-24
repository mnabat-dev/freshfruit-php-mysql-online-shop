<?php
$servername = "localhost";
$port = 3306;
$username = "root";
$passwort = "";
$dbname = "mydb";
$conn = new mysqli($servername, $username, $passwort, $dbname, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "UPDATE user SET status = 'abgemeldet' WHERE status = 'angemeldet'";
$result = mysqli_query($conn, $sql);


$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputEmail = trim($_POST['email'] ?? '');
    $inputPassword = $_POST['password'] ?? '';

    if ($inputEmail === '' || $inputPassword === '') {
        $error = "Bitte geben Sie E-Mail und Passwort ein.";
    } else {
        $sql = "SELECT passwort, rolle FROM user WHERE email = '" . mysqli_real_escape_string($conn, $inputEmail) . "'";
        $result = mysqli_query($conn, $sql);

        if ($result && $row = mysqli_fetch_assoc($result)) {
            if (password_verify($inputPassword, $row['passwort'])) {
                $sql = "UPDATE user SET status = 'angemeldet' WHERE email = '" . mysqli_real_escape_string($conn, $inputEmail) . "'";
                mysqli_query($conn, $sql);

                if ($row['rolle'] == 'admin') {
                    header('Location: /Projekt Shop/user/admin.php');
                    exit;
                }
                header('Location: Index.php');
                exit;
            }
        }

        $error = "E-Mail oder Passwort ist falsch.";
        mysqli_close($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <title>Login</title>
    <style>
        .form-container {
            width: 70%;
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
       <a href="startseite.php"><span class="material-symbols-outlined">arrow_back</span></a> <a class="willkommen">Login - Shop</a> <a href="startseite.php"> Startseite </a><a href="Index.php#produkte"> Produkte </a> <a href="Index.php#kontakt"> Kontakt</a>  
    </nav>

<main class="main-content">
    <h2 class="produkte" style="max-width: 900px; margin: 20px auto;">Anmelden</h2>
    <div class="form-container">


        <table>
            <caption>Melden Sie sich mit Ihrem Konto an, um Die Produkte anzu sehen und oder zu Kaufen.</caption>
            <thead>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <tr>    
                    <th><label for="email">E-Mail:</label></th>
                    <td><input style="width: 90%;" type="email" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required></td>
                </tr>

        </thead>
        <tbody>
            <tr>
                <th><label for="password">Passwort:</label></th>
                <td><input style="width: 90%;" type="password" id="password" name="password" required></td>                            
            </tr>

        </tbody>
        <tfoot>
            <tr>
                <td colspan="2"><button type="submit"><span class="material-symbols-outlined">login</span>Anmelden</button></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center; padding-top: 20px;"><a href="/Projekt Shop/user/register.php">Noch kein Konto? Registrieren</a></td>
            </tr>
        </tfoot>
    </table>
            <?php if ($error !== '') : ?>
            <div style="color: #a33; margin-bottom: 1rem;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        </form>
        
    </div>
</main>
    <footer class="footer" id="kontakt">
        <p>Autor: Mahmoud Nabat</p>
        <p><a href="mailto:nabat@gmail.com">nabat@gmail.com</a></p>
    </footer>
</body>
</html>