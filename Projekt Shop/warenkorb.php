<?php
$servername = "localhost";
$username = "root";
$passwort = "";
$dbname = "mydb";
$port = 3306;

$conn = mysqli_connect($servername, $username, $passwort, $dbname, $port);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


$sql = "SELECT status, iduser FROM user WHERE rolle = 'user'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$status = $row['status'];
$userid = $row['iduser'];

if ($status != 'angemeldet') {
    header('Location: login.php');
    exit;
}

// POST-Handler: Änderungen direkt in der Datenbank
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Warenkorb komplett leeren (heutige Bestellungen)
    if (isset($_POST['clear']) && $_POST['clear'] == '1') {
        mysqli_query($conn, "DELETE FROM bestellung WHERE datum = CURDATE()");
        header('Location: warenkorb.php');
        exit;
    }

    // Menge um 1 verringern
    if (isset($_POST['decrement']) && is_numeric($_POST['decrement'])) {
        $id = (int)$_POST['decrement'];
        mysqli_query($conn, "UPDATE bestellung SET menge = menge - 1 WHERE idbestellung = $id");
        mysqli_query($conn, "DELETE FROM bestellung WHERE idbestellung = $id AND menge <= 0");
        header('Location: warenkorb.php');
        exit;
    }

    // Menge um 1 erhöhen
    if (isset($_POST['increment']) && is_numeric($_POST['increment'])) {
        $id = (int)$_POST['increment'];
        mysqli_query($conn, "UPDATE bestellung SET menge = menge + 1 WHERE idbestellung = $id");
        header('Location: warenkorb.php');
        exit;
    }

    // Artikel komplett entfernen
    if (isset($_POST['remove']) && is_numeric($_POST['remove'])) {
        $id = (int)$_POST['remove'];
        mysqli_query($conn, "DELETE FROM bestellung WHERE idbestellung = $id");
        header('Location: warenkorb.php');
        exit;
    }
}

// Bestellungen für heute laden
$orders = [];
$sql = "SELECT * FROM bestellung WHERE kategorieid =1";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $orders[] = $row;
}

// Hilfsfunktion für sichere Ausgabe
function e($s) {
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="css.css">
    <title>Warenkorb</title>
    <style>
        table { border-collapse: collapse; width: 100%; max-width: 900px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f5f5f5; font-weight: bold; }
        .actions form { display:inline; margin-right: 5px; }
        .actions button { padding: 5px 10px; }
        .center { max-width: 900px; margin: 20px auto; padding: 0 20px; }
        img { max-width: 100px; height: auto; border-radius: 5px; }
        .preis { text-align: right; }
        tfoot tr { font-weight: bold; background-color: #f9f9f9; }
    </style>
</head>
<body>
<nav class="navbar">
    <a href="Index.php"><span class="material-symbols-outlined">arrow_back</span> Zurück zum Shop</a>
</nav>

<main class="center">
    <h1>Dein Warenkorb</h1>

    <?php 
    

    
    if (!empty($orders)): 
    ?>

        <table>
            <thead>
                <tr>
                    <th>Bild</th>
                    <th>Produkt</th>
                    <th>Preis/Stück</th>
                    <th style="text-align: center;">Menge</th>
                    <th class="preis">Zwischensumme</th>
                    <th style="width: 180px;">Aktionen</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $gesamt = 0.0;
                foreach ($orders as $order):
                    $name = $order['name'];
                    $preis = (double)$order['preis'];
                    $bildpfad = $order['bildpfad'];
                    $anz = (int)$order['menge'];
                    $subtotal = $preis * $anz;
                    $gesamt += $subtotal;
                ?>
                <tr>
                    <td><img src="<?php echo e($bildpfad); ?>" alt="<?php echo e($name); ?>"></td>
                    <td><?php echo e($name); ?></td>
                    <td class="preis"><?php echo $preis > 0 ? number_format($preis, 2, ',', ' ') . ' €' : '-'; ?></td>
                    <td style="text-align: center;">
                        <form method="post" style="display:inline">
                            <input type="hidden" name="decrement" value="<?php echo (int)$order['idbestellung']; ?>">
                            <button type="submit" style="padding: 3px 8px; background-color: none; color: red; border: none; cursor: pointer;"><span class="material-symbols-outlined">remove_shopping_cart</span></button>
                        </form>
                        <span style="margin: 0 10px; font-weight: bold;"><?php echo $anz; ?></span>
                        <form method="post" style="display:inline">
                            <input type="hidden" name="increment" value="<?php echo (int)$order['idbestellung']; ?>">
                            <button type="submit" style="padding: 3px 8px; background-color: none; color: blue; border: none; cursor: pointer;"><span class="material-symbols-outlined">add_shopping_cart</span></button>
                        </form>
                    </td>
                    <td class="preis"><?php echo $preis > 0 ? number_format($subtotal, 2, ',', ' ') . ' €' : '-'; ?></td>
                    <td class="actions">
                        <form method="post" style="display:inline">
                            <input type="hidden" name="remove" value="<?php echo (int)$order['idbestellung']; ?>">
                            <button type="submit" style="padding: 3px 8px; background-color: none; color: #f44336; border: none; cursor: pointer;"><span class="material-symbols-outlined" style="color: #f44336;">cancel_presentation</span>Entfernen</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">Gesamtanzahl Artikel:</td>
                    <td style="text-align: center;">
                        <?php
                        $totalCount = 0;
                        foreach ($orders as $order) {
                            $totalCount += (int)$order['menge'];
                        }
                        echo $totalCount;
                        ?>
                    </td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td colspan="4">Gesamtpreis:</td>
                    <td class="preis"><?php echo $gesamt > 0 ? number_format($gesamt, 2, ',', ' ') . ' €' : '0,00 €'; ?></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <p style="margin-top: 30px; display: flex; gap: 15px;">
            <form method="post" style="display:inline">
                <input type="hidden" name="clear" value="1">
                <button type="submit" class="btn"><span class="material-symbols-outlined">delete</span>Warenkorb leeren</button>
            </form>
            <button onclick="window.location.href='Index.php'" class="btn" style=" text-align: center; text-decoration: none; display: inline-block;"><span class="material-symbols-outlined">arrow_back</span> Weiter einkaufen</button>
            <button onclick="window.location.href='kasse.php'" class="btn" style="cursor: pointer;"> <span class="material-symbols-outlined">shopping_cart_checkout</span> Zur Kasse</button>
        </p>

    <?php else: ?>

        <p style="font-size: 18px; margin-top: 30px;">Dein Warenkorb ist leer.</p>
        <p><a href="Index.php" class="btn" style="padding: 10px 20px; text-decoration: none; display: inline-block;"><span class="material-symbols-outlined">arrow_back</span> Zurück zum Shop</a></p>

    <?php endif; ?>

</main>
    <footer class="footer" id="kontakt">
        <p>Autor: Mahmoud Nabat</p>
        <p><a href="mailto:nabat@gmail.com">nabat@gmail.com</a></p>
        <p><a href="/Projekt Shop/user/login.php">Anmelden</a></p>
    </footer>
</body>
</html>