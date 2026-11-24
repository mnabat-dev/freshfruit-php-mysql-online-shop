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

$sql = "SELECT status FROM user WHERE rolle = 'admin'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$status = $row['status'];

if ($status != 'angemeldet') {
    header('Location: ../login.php');
    exit;
}

$message = '';

// Wenn ein Wert kopiert wurde, wird per GET-Parameter eine Nachricht generiert
if (isset($_GET['copied'])) {
    $copied = trim($_GET['copied']);
    if ($copied !== '') {
        $message = "Copied: " . htmlspecialchars($copied);
    }
}


$iduser = array();
$username = array();
$telefonnummer = array();
$email = array();
$passwort = array();
$plz = array();
$ort = array();
$strasse = array();
$hausnummer = array();
$rolle = array();
$status = array();

$sql = "SELECT * FROM user ORDER BY iduser";
$result = mysqli_query($conn, $sql);

while ($row =  mysqli_fetch_assoc($result)) {
    $iduser[] = $row['iduser'];
    $username[] = $row['name'];
    $telefonnummer[] = $row['Telefonnummer'];
    $email[] = $row['email'];
    $passwort[] = $row['passwort'];
    $plz[] = $row['PLZ'];
    $ort[] = $row['Ort'];
    $strasse[] = $row['Strasse'];
    $hausnummer[] = $row['Hausnummer'];
    $rolle[] = $row['rolle'];
    $status[] = $row['status'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'name_ändern':
            if (isset($_POST['ID'], $_POST['neuer_name'])) {
                $id   = (int)($_POST['ID']);
                $name = trim($_POST['neuer_name'] ?? '');
                $sql = "UPDATE user SET name = '" . mysqli_real_escape_string($conn, $name) . "' WHERE iduser = " . $id;
                if (mysqli_query($conn, $sql)) {
                    $message = "Name erfolgreich geändert.";
                } else {
                    $message = "Fehler beim Ändern des Namens: " . mysqli_error($conn);
                }
            } else {
                $message = "Ungültige Eingabe für Name ändern.";
            }
            break;

        case 'telefon_ändern':
            if (isset($_POST['ID'], $_POST['neue_telefonnummer'])) {
                $id  = (int)($_POST['ID'] ?? 0);
                $tel = trim($_POST['neue_telefonnummer'] ?? '');
                $sql = "UPDATE user SET telefonnummer = '" . mysqli_real_escape_string($conn, $tel) . "' WHERE iduser = " . $id;
                if (mysqli_query($conn, $sql)) {
                    $message = "Telefonnummer erfolgreich geändert.";
                } else {
                    $message = "Fehler beim Ändern der Telefonnummer: " . mysqli_error($conn);
                }
            } else {
                $message = "Ungültige Eingabe für Telefonnummer ändern.";
            }
            break;

        case 'email_ändern':
            if (isset($_POST['ID'], $_POST['neue_email'])) {
                $id    = (int)($_POST['ID'] ?? 0);
                $email = trim($_POST['neue_email'] ?? '');
                $sql = "UPDATE user SET email = '" . mysqli_real_escape_string($conn, $email) . "' WHERE iduser = " . $id;
                if (mysqli_query($conn, $sql)) {
                    $message = "E-Mail erfolgreich geändert.";
                } else {
                    $message = "Fehler beim Ändern der E-Mail: " . mysqli_error($conn);
                }
            } else {
                $message = "Ungültige Eingabe für E-Mail ändern.";
            }
            break;

        case 'passwort_ändern':
            if (isset($_POST['ID'], $_POST['neue_passwort'], $_POST['neues_passwort_bestätigen'])) {
                if ($_POST['neue_passwort'] !== $_POST['neues_passwort_bestätigen']) {
                    $message = "Die Passwörter stimmen nicht überein.";
                } else {
                    $id       = (int)($_POST['ID'] ?? 0);
                    $passwort = trim($_POST['neue_passwort'] ?? '');
                    $sql = "UPDATE user SET passwort = '" . mysqli_real_escape_string($conn, $passwort) . "' WHERE iduser = " . $id;
                    if (mysqli_query($conn, $sql)) {
                        $message = "Passwort erfolgreich geändert.";
                    } else {
                        $message = "Fehler beim Ändern des Passworts: " . mysqli_error($conn);
                    }
                }
            } else {
                $message = "Ungültige Eingabe für Passwort ändern.";
            }
            break;

        case 'adresse_ändern':
            if (isset($_POST['ID'], $_POST['neue_plz'], $_POST['neuer_ort'], $_POST['neue_strasse'], $_POST['neue_hausnummer'])) {
                $id         = (int)($_POST['ID'] ?? 0);
                $plz        = trim($_POST['neue_plz'] ?? '');
                $ort        = trim($_POST['neuer_ort'] ?? '');
                $strasse    = trim($_POST['neue_strasse'] ?? '');
                $hausnummer = trim($_POST['neue_hausnummer'] ?? '');

                $sql = "UPDATE user SET plz = '" . mysqli_real_escape_string($conn, $plz) . "', ort = '" . mysqli_real_escape_string($conn, $ort) . "', strasse = '" . mysqli_real_escape_string($conn, $strasse) . "', hausnummer = '" . mysqli_real_escape_string($conn, $hausnummer) . "' WHERE iduser = " . $id;
                if (mysqli_query($conn, $sql)) {
                    $message = "Adresse erfolgreich geändert.";
                } else {
                    $message = "Fehler beim Ändern der Adresse: " . mysqli_error($conn);
                }
            } else {
                $message = "Ungültige Eingabe für Adresse ändern.";
            }
            break;

        case 'rolle_ändern':
            if (isset($_POST['ID'], $_POST['neue_rolle'])) {
                $id    = (int)($_POST['ID'] ?? 0);
                $rolle = trim($_POST['neue_rolle'] ?? '');
                $sql = "UPDATE user SET rolle = '" . mysqli_real_escape_string($conn, $rolle) . "' WHERE iduser = " . $id;
                if (mysqli_query($conn, $sql)) {
                    $message = "Rolle erfolgreich geändert.";
                } else {
                    $message = "Fehler beim Ändern der Rolle: " . mysqli_error($conn);
                }
            } else {
                $message = "Ungültige Eingabe für Rolle ändern.";
            }
            break;

        case 'benutzer_löschen':
            if (isset($_POST['ID'])) {
                $id  = (int)($_POST['ID'] ?? 0);
                $sql = "DELETE FROM user WHERE iduser = " . $id;
                if (mysqli_query($conn, $sql)) {
                    $message = "Benutzer erfolgreich gelöscht.";
                } else {
                    $message = "Fehler beim Löschen des Benutzers: " . mysqli_error($conn);
                }
            } else {
                $message = "Ungültige Eingabe für Benutzer löschen.";
            }
            break;

        case 'benutzer_erstellen':
            if (isset($_POST['name'], $_POST['telefonnummer'], $_POST['email'], $_POST['passwort'], $_POST['bestätigung_passwort'], $_POST['plz'], $_POST['ort'], $_POST['strasse'], $_POST['hausnummer'], $_POST['Status'], $_POST['Rolle'])) {
                $sql = "INSERT INTO user (name, telefonnummer, email, passwort, plz, ort, strasse, hausnummer, rolle, status) VALUES ('" . mysqli_real_escape_string($conn, $_POST['name']) . "', '" . mysqli_real_escape_string($conn, $_POST['telefonnummer']) . "', '" . mysqli_real_escape_string($conn, $_POST['email']) . "', '" . mysqli_real_escape_string($conn, $_POST['passwort']) . "', '" . mysqli_real_escape_string($conn, $_POST['plz']) . "', '" . mysqli_real_escape_string($conn, $_POST['ort']) . "', '" . mysqli_real_escape_string($conn, $_POST['strasse']) . "', '" . mysqli_real_escape_string($conn, $_POST['hausnummer']) . "', '" . mysqli_real_escape_string($conn, $_POST['Rolle']) . "', 'abgemeldet')";
                if (mysqli_query($conn, $sql)) {
                    $message = "Benutzer erfolgreich erstellt.";
                } else {
                    $message = "Fehler beim Erstellen des Benutzers: " . mysqli_error($conn);
                }
            } else {
                $message = "Ungültige Eingabe für Benutzer erstellen.";
            }
            break;
          case 'abmelden':
            $sql = "UPDATE user SET status = 'abgemeldet' WHERE rolle = 'admin'";
            mysqli_query($conn, $sql);
            header('Location: ../login.php');
            exit;
            break;

        case 'artikelname_form':
            if (isset($_POST['artikelID'], $_POST['artikelname'])) {
                $id   = (int)($_POST['artikelID']);
                $name = trim($_POST['artikelname'] ?? '');
                $sql = "UPDATE artikel SET Artikel_name = '" . mysqli_real_escape_string($conn, $name) . "' WHERE idArtikel = " . $artikelID;
                if (mysqli_query($conn, $sql)) {
                    $message = "Artikel Name erfolgreich geändert.";
                } else {
                    $message = "Fehler beim Ändern des Artikel Namens: " . mysqli_error($conn);
                }
            } else {
                $message = "Ungültige Eingabe für Artikel Name ändern.";
            }
            break;

        case 'artikelname_form':
            if (isset($_POST['artikelID'], $_POST['artikelname'])) {
                $id   = (int)($_POST['artikelID']);
                $name = trim($_POST['artikelname'] ?? '');
                $sql = "UPDATE artikel SET Artikel_name = '" . mysqli_real_escape_string($conn, $name) . "' WHERE idArtikel = " . $artikelID;
                if (mysqli_query($conn, $sql)) {
                    $message = "Artikel Name erfolgreich geändert.";
                } else {
                    $message = "Fehler beim Ändern des Artikel Namens: " . mysqli_error($conn);
                }
            } else {
                $message = "Ungültige Eingabe für Artikel Name ändern.";
            }
            break;

        case 'artikelbeschreibung_form':
            if (isset($_POST['artikelID'], $_POST['artikelbeschreibung'])) {
                $id  = (int)($_POST['artikelID'] ?? 0);
                $beschreibung = trim($_POST['artikelbeschreibung'] ?? '');
                $sql = "UPDATE artikel SET Artikel_beschreibung = '" . mysqli_real_escape_string($conn, $beschreibung) . "' WHERE idArtikel = " . $artikelID;
                if (mysqli_query($conn, $sql)) {
                    $message = "Artikel Beschreibung erfolgreich geändert.";
                } else {
                    $message = "Fehler beim Ändern der Beschreibung für Artikel: " . mysqli_error($conn);
                }
            } else {
                $message = "Ungültige Eingabe für Artikel Beschreibung ändern.";
            }
            break;

        case 'artikelkid_form':
            if (isset($_POST['artikelID'], $_POST['artikelkid'])) {
                $id  = (int)($_POST['artikelID'] ?? 0);
                $kid = trim($_POST['artikelbeschreibung'] ?? '');
                $sql = "UPDATE artikel SET kategorie_idkategorie = '" . mysqli_real_escape_string($conn, $kid) . "' WHERE idArtikel = " . $artikelID;
                if (mysqli_query($conn, $sql)) {
                    $message = "Artikel Kategorie ID erfolgreich geändert.";
                } else {
                    $message = "Fehler beim Ändern der Kategorie ID: " . mysqli_error($conn);
                }
            } else {
                $message = "Ungültige Eingabe für Kategorie ID ändern.";
            }
            break;


        case 'artikelbild_form':
            if (isset($_POST['artikelID'], $_POST['artikelbild'])) {
                $id  = (int)($_POST['artikelID'] ?? 0);
                $bild = trim($_POST['artikelbild'] ?? '');
                $sql = "UPDATE artikel SET Artikel_bild = '" . mysqli_real_escape_string($conn, $bild) . "' WHERE idArtikel = " . $artikelID;
                if (mysqli_query($conn, $sql)) {
                    $message = "Artikel Bild erfolgreich geändert.";
                } else {
                    $message = "Fehler beim Ändern des Artikel-Bilds: " . mysqli_error($conn);
                }
            } else {
                $message = "Ungültige Eingabe für Artikel-Bild ändern.";
            }
            break;

        case 'artikelpreis_form':
            if (isset($_POST['artikelID'], $_POST['artikelpreis'])) {
                $id  = (int)($_POST['artikelID'] ?? 0);
                $preis = trim($_POST['artikelpreis'] ?? '');
                $sql = "UPDATE artikel SET Artikel_preis = '" . mysqli_real_escape_string($conn, $preis) . "' WHERE idArtikel = " . $artikelID;
                if (mysqli_query($conn, $sql)) {
                    $message = "Artikel Preis erfolgreich geändert.";
                } else {
                    $message = "Fehler beim Ändern des Artikel-Preises: " . mysqli_error($conn);
                }
            } else {
                $message = "Ungültige Eingabe für Artikel-Preis ändern.";
            }
            break;

        case 'artikellöschen':
            if (isset($_POST['artikelID'])) {
                $id  = (int)($_POST['artikelID'] ?? 0);
                $sql = "DELETE FROM artikel WHERE idArtikel = " . $id;
                if (mysqli_query($conn, $sql)) {
                    $message = "Artikel erfolgreich gelöscht.";
                } else {
                    $message = "Fehler beim Löschen des Artikels: " . mysqli_error($conn);
                }
            } else {
                $message = "Ungültige Eingabe für Artikel löschen.";
            }
            break;

        case 'artikelerstellen_form':
            if (isset($_POST['artikelpreis'], $_POST['artikelname'], $_POST['artikelbeschreibung'], $_POST['artikelkid'], $_POST['artikelbildpfad'])) {
                $preis = trim($_POST['artikelpreis'] ?? '');
                $name = trim($_POST['artikelname'] ?? '');
                $beschreibung = trim($_POST['artikelbeschreibung'] ?? '');
                $kid = trim($_POST['artikelkid'] ?? '');
                $bild = trim($_POST['artikelbildpfad'] ?? '');
                $sql = "Select idArtikel from artikel where Artikel_name = '" . mysqli_real_escape_string($conn, $name) . "'";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $message = "Artikel mit diesem Namen existiert bereits.";
                } else {
                    if(!empty($preis) && !empty($name) && !empty($beschreibung) && !empty($kid) && !empty($bild)){
                        $sql = "INSERT INTO artikel ( Artikel_name, Artikel_beschreibung, kategorie_idkategorie, Artikel_bild, Artikel_preis) VALUES ('" . mysqli_real_escape_string($conn, $name) . "', '" . mysqli_real_escape_string($conn, $beschreibung) . "', '" . mysqli_real_escape_string($conn, $kid) . "', '" . mysqli_real_escape_string($conn, $bild) . "', '" . mysqli_real_escape_string($conn, $preis) . "')";
                        if (mysqli_query($conn, $sql)) {
                            $message = "Artikel erfolgreich erstellt.";
                        } else {
                            $message = "Fehler beim Erstellen des Artikel: " . mysqli_error($conn);
                        }
                    }
                }
            } else {
                $message = "Ungültige Eingabe für Artikel erstellen.";
            }
            break;

        case 'kategorie_name_form':
            if (isset($_POST['kategorieID'], $_POST['kategoriename'])) {
                $id  = (int)($_POST['kategorieID'] ?? 0);
                $name = trim($_POST['kategoriename'] ?? '');
                $sql = "UPDATE kategorie SET Kategorie_name = '" . mysqli_real_escape_string($conn, $name) . "' WHERE idKategorie = " . $id;
                if (mysqli_query($conn, $sql)) {
                    $message = "Kategorie erfolgreich geändert.";
                } else {
                    $message = "Fehler beim Ändern der Kategorie: " . mysqli_error($conn);
                }
            } else {
                $message = "Ungültige Eingabe für Kategorie ändern.";
            }
            break;

        case 'kategorie_beschreibung_form':
            if (isset($_POST['kategorieID'], $_POST['kategorie_beschreibung'])) {
                $id  = (int)($_POST['kategorieID'] ?? 0);
                $beschreibung = trim($_POST['kategorie_beschreibung'] ?? '');
                $sql = "UPDATE kategorie SET Kategorie_beschreibung = '" . mysqli_real_escape_string($conn, $beschreibung) . "' WHERE idKategorie = " . $id;
                if (mysqli_query($conn, $sql)) {
                    $message = "Kategorie Beschreibung erfolgreich geändert.";
                } else {
                    $message = "Fehler beim Ändern der Kategorie Beschreibung: " . mysqli_error($conn);
                }
            } else {
                $message = "Ungültige Eingabe für Kategorie Beschreibung ändern.";
            }
            break;

        case 'kategorieerstellen':
            if (isset($_POST['kategoriename'], $_POST['kategorie_beschreibung'])) {
                $name  = (int)($_POST['kategoriename'] ?? '');
                $beschreibung = trim($_POST['kategorie_beschreibung'] ?? '');
                $sql = "INSERT INTO kategorie (kategorie_name, kategorie_beschreibung) VALUES ('" . mysqli_real_escape_string($conn, $name) . "', '" . mysqli_real_escape_string($conn, $beschreibung) . "')";
                if (mysqli_query($conn, $sql)) {
                    $message = "Kategorie erfolgreich erstellt.";
                } else {
                    $message = "Fehler beim Erstellen der Kategorie: " . mysqli_error($conn);
                }
            } else {
                $message = "Ungültige Eingabe für Kategorie erstellen.";
            }
            break;
        case 'kategorielöschen':
            if (isset($_POST['kategorieID'])) {
                $id  = (int)($_POST['kategorieID'] ?? 0);
                $sql = "DELETE FROM kategorie WHERE idKategorie = " . $id;
                if (mysqli_query($conn, $sql)) {
                    $message = "Kategorie erfolgreich gelöscht.";
                } else {
                    $message = "Fehler beim Löschen der Kategorie: " . mysqli_error($conn);
                }
            } else {
                $message = "Ungültige Eingabe für Kategorie löschen.";
            }
            break;
          case 'lieferrantlöschen':
            if (isset($_POST['lieferrantID'])) {
                $id  = (int)($_POST['lieferrantID'] ?? 0);
                $sql = "DELETE FROM lieferrant WHERE idLieferrant = " . $id;
                if (mysqli_query($conn, $sql)) {
                    $message = "Lieferrant erfolgreich gelöscht.";
                } else {
                    $message = "Fehler beim Löschen der Lieferrant: " . mysqli_error($conn);
                }
            } else {
                $message = "Ungültige Eingabe für Lieferrant löschen.";
            }
            break;
          case 'lieferranterstellen':
            if (isset($_POST['lieferrant_name'], $_POST['lieferrant_telefonnummer'], $_POST['lieferrant_email'])) {
                $name = trim($_POST['lieferrant_name'] ?? '');
                $telefonnummer = trim($_POST['lieferrant_telefonnummer'] ?? '');
                $email = trim($_POST['lieferrant_email'] ?? '');
                $sql = "INSERT INTO lieferrant (lieferrant_name, lieferrant_telefonnummer, lieferrant_email) VALUES ('" . mysqli_real_escape_string($conn, $name) . "', '" . mysqli_real_escape_string($conn, $telefonnummer) . "', '" . mysqli_real_escape_string($conn, $email) . "')";
                if (mysqli_query($conn, $sql)) {
                    $message = "Lieferrant erfolgreich erstellt.";
                } else {
                    $message = "Fehler beim Erstellen der Lieferrant: " . mysqli_error($conn);
                }
            } else {
                $message = "Ungültige Eingabe für Lieferrant erstellen.";
            }
            break;
          case 'lieferrant_name_form':
            if (isset($_POST['lieferrant_name'], $_POST['lieferrantID'])) {
                $name = trim($_POST['lieferrant_name'] ?? '');
                $id = (int)($_POST['lieferrantID'] ?? 0);
                $sql = "UPDATE lieferrant SET lieferrant_name = '" . mysqli_real_escape_string($conn, $name) . "' WHERE idlieferrant = " . $id;
                if (mysqli_query($conn, $sql)) {
                    $message = "Lieferranten Name erfolgreich Geändert.";
                } else {
                    $message = "Fehler beim Ändern der Lieferranten Name: " . mysqli_error($conn);
                }
            } else {
                $message = "Ungültige Eingabe für Lieferranten Name Ändern.";
            }
            break;
          case 'lieferrant_telefonnummer_form':
            if (isset($_POST['lieferrant_telefonnummer'], $_POST['lieferrantID'])) {
                $telefonnummer = trim($_POST['lieferrant_telefonnummer'] ?? '');
                $id = (int)($_POST['lieferrantID'] ?? 0);
                $sql = "UPDATE lieferrant SET lieferrant_telefonnummer = '" . mysqli_real_escape_string($conn, $telefonnummer) . "' WHERE idlieferrant = " . $id;
                if (mysqli_query($conn, $sql)) {
                    $message = "Lieferranten Telefonnummer erfolgreich Geändert.";
                } else {
                    $message = "Fehler beim Ändern der Lieferranten Telefonnummer: " . mysqli_error($conn);
                }
            } else {
                $message = "Ungültige Eingabe für Lieferranten Telefonnummer Ändern.";
            }
            break;
          case 'lieferrant_email_form':
            if (isset($_POST['lieferrant_email'], $_POST['lieferrantID'])) {
                $email = trim($_POST['lieferrant_email'] ?? '');
                $id = (int)($_POST['lieferrantID'] ?? 0);
                $sql = "UPDATE lieferrant SET lieferrant_email = '" . mysqli_real_escape_string($conn, $email) . "' WHERE idlieferrant = " . $id;
                if (mysqli_query($conn, $sql)) {
                    $message = "Lieferranten Email erfolgreich Geändert.";
                } else {
                    $message = "Fehler beim Ändern der Lieferranten Email: " . mysqli_error($conn);
                }
            } else {
                $message = "Ungültige Eingabe für Lieferranten Email Ändern.";
            }
            break;
        default:
            $message = "Unbekannte Aktion.";
            break;
    }
}

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css.css">
    <title>Admin-Bereich</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px; 
        }
        .message { 
            margin-bottom: 10px; 
            padding: 15px; 
            background: #eef; 
            border: 1px solid #99c; 
            border-radius: 10px; 
        }

table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #b7b3b3;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
td{
    transition: background-color 0.5s ease;
}
td:hover {
  background-color: #5cf536;
  transition: background-color 0.5s ease;
}
details {
  width: 500px;
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 10px;
  background: #f9f9f9;
  transition: all 0.3s ease;
}

summary {
  display: flex;
  justify-content: space-between;
  align-items: center;
  cursor: pointer;
  list-style: none;
}

summary::-webkit-details-marker {
  display: none;
}

.icon {
  transition: transform 0.3s;
}
details[open] {
  border-color: #1e9400;
}
details:not([open]):hover {
    .icon {
        transform: rotate(180deg) scale(1.2);
    }
}
details[open] .icon:hover {
    transform: rotate(0deg) scale(1.2);
}
details[open] .icon {
  transform: rotate(180deg);
}

table {
  margin-top: 10px;
}

input {
  padding: 5px;
  border-radius: 5px;
  border: 1px solid #ccc;
}

</style>
</head>
<body>
    <nav class="navbar">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <button style="background: none; border: none; cursor: pointer; color: white; font-size: large;" type="submit" name="action" value="abmelden">
                <span class="material-symbols-outlined">arrow_back</span> Abmelden
            </button>
        </form>
        <a class="willkommen">Admin-Bereich</a>
    </nav>

    <?php if ($message !== ''){
        echo '<div class="message">' . htmlspecialchars($message) . '</div>';
     } ?>

    <h2>Benutzer & Rollen</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Telefonnummer</th>
                <th>E-Mail</th>
                <th>Passwort</th>
                <th>PLZ</th>
                <th>Ort</th>
                <th>Strasse</th>
                <th>Hausnummer</th>
                <th>Status</th>
                <th>Rolle</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $i < count($iduser); $i++)
              echo'  <tr>
                    <td> <button style="background: none; border: none; cursor: pointer;" type="button" onclick="copyToClipboard(\'' . addslashes($iduser[$i]) . '\')"><span class="material-symbols-outlined">content_copy</span></button> ' . htmlspecialchars($iduser[$i]) . '</td>
                    <td>' . htmlspecialchars($username[$i]) . '</td>
                    <td>' . htmlspecialchars($telefonnummer[$i]) . '</td>
                    <td>' . htmlspecialchars($email[$i]) . '</td>
                    <td>' . htmlspecialchars($passwort[$i]) . '</td>
                    <td>' . htmlspecialchars($plz[$i]) . '</td>
                    <td>' . htmlspecialchars($ort[$i]) . '</td>
                    <td>' . htmlspecialchars($strasse[$i]) . '</td>
                    <td>' . htmlspecialchars($hausnummer[$i]) . '</td>
                    <td>' . htmlspecialchars($rolle[$i]) . '</td>
                    <td>' . htmlspecialchars($status[$i]) . '</td>
                </tr>';
             ?>
        </tbody>
    </table>
<br>
<details>
  <summary>Name ändern <span class="material-symbols-outlined icon">arrow_downward</span></summary>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
  <table>
    <tr>
      <td>ID:</td>
      <td><input type="number_format" name="ID"></td>
    </tr>
    <tr>
      <td>Name: </td>
      <td><input type="text" name="neuer_name"></td>
    </tr>
        <tfoot>
    <tr>
      <td colspan="2"><button style="margin-left: 150px;" class="btn" type="submit" name="action" value="name_ändern">Name ändern</button></td>
    </tr>
    </tfoot>
  </table>
  </form>
</details> 

<details>
  <summary>Telefonnummer ändern <span class="material-symbols-outlined icon">arrow_downward</span></summary>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
  <table>
    <tr>
      <td>ID:</td>
      <td><input type="number_format" name="ID"></td>
    </tr>
    <tr>
      <td>Telefonnummer:</td>
      <td><input type="number_format" name="neue_telefonnummer"></td>
    </tr>
        <tfoot>
    <tr>
      <td colspan="2"><button style="margin-left: 150px;" class="btn" type="submit" name="action" value="telefonnummer_ändern">Telefonnummer ändern</button></td>
    </tr>
    </tfoot>
  </table>
  </form>
</details> 
 
<details>
  <summary>E-Mail ändern <span class="material-symbols-outlined icon">arrow_downward</span></summary>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
  <table>
    <tr>
      <td>ID:</td>
      <td><input type="number_format" name="ID"></td>
    </tr>
    <tr>
      <td>E-Mail:</td>
      <td><input type="email" name="neue_email"></td>
    </tr>
        <tfoot>
    <tr>
      <td colspan="2"><button style="margin-left: 150px;" class="btn" type="submit" name="action" value="email_ändern">E-Mail ändern</button></td>
    </tr>
    </tfoot>
  </table>
  </form>
</details> 

<details>
  <summary>Passwort ändern <span class="material-symbols-outlined icon">arrow_downward</span></summary>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
  <table>
    <tr>
      <td>ID:</td>
      <td><input type="number_format" name="ID"></td>
    </tr>
    <tr>
      <td>Passwort:</td>
      <td><input type="password" name="neues_passwort"></td>
    </tr>
    <tr>
      <td>Passwort bestätigen:</td>
      <td><input type="password" name="neues_passwort_bestätigen"></td>
    </tr>
        <tfoot>
    <tr>
      <td colspan="2"><button style="margin-left: 150px;" class="btn" type="submit" name="action" value="passwort_ändern">Passwort ändern</button></td>
    </tr>
    </tfoot>
  </table>
  </form>
</details> 

<details>
    <summary>Adresse ändern <span class="material-symbols-outlined icon">arrow_downward</span></summary>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
  <table>
    <tr>
      <td>ID:</td>
      <td><input type="number_format" name="ID"></td>
    </tr>
    <tr>
      <td>PLZ:</td>
      <td><input type="number_format" name="neue_plz"></td>
    </tr>
    <tr>
      <td>Ort:</td>
      <td><input type="text" name="neuer_ort"></td>
    </tr>
    <tr>
      <td>Strasse:</td>
      <td><input type="text" name="neue_strasse"></td>
    </tr>
    <tr>
      <td>Hausnummer:</td>
      <td><input type="text" name="neue_hausnummer"></td>
    </tr>
    <tfoot>
    <tr>
      <td colspan="2"><button style="margin-left: 150px;" class="btn" type="submit" name="action" value="adresse_ändern">Adresse ändern</button></td>
    </tr>
    </tfoot>
  </table>
  </form>
</details> 

<details>
  <summary>Rolle ändern <span class="material-symbols-outlined icon">arrow_downward</span></summary>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
  <table>
    <tr>
      <td>ID:</td>
      <td><input type="number_format" name="ID"></td>
    </tr>
    <tr>
      <td>Rolle:</td>
      <td><select name="neue_rolle">
        <option value="admin">Admin</option>
        <option value="user">User</option>
      </select></td>
    </tr>
<tfoot>
    <tr>
      <td colspan="2"><button style="margin-left: 150px;" class="btn" type="submit" name="action" value="rolle_ändern">Rolle ändern</button></td>
    </tr>
    </tfoot>
  </table>
  </form>
</details> 

<details>
  <summary>Benutzer Löschen<span class="material-symbols-outlined icon">arrow_downward</span></summary>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
  <table>
    <tr>
      <td>ID:</td>
      <td><input type="number_format" name="ID"></td>
    </tr>
<tfoot>
    <tr>
      <td colspan="2"><button style="margin-left: 150px;" class="btn" type="submit" name="action" value="benutzer_löschen">Benutzer löschen</button></td>
    </tr>
    </tfoot>
  </table>
  </form>
</details> 

<details>
  <summary>Benutzer Erstellen<span class="material-symbols-outlined icon">arrow_downward</span></summary>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
  <table>
    <tr>
      <td>Name:</td>
      <td><input type="text" name="Name"></td>
    </tr>

    <tr>
      <td>Telefonnummer:</td>
      <td><input type="number_format" name="telefonnummer"></td>
    </tr>
    <tr>
      <td>E-Mail:</td>
      <td><input type="email" name="email"></td>
    </tr>
    <tr>
      <td>Passwort:</td>
      <td><input type="password" name="passwort"></td>
    </tr>
    <tr>
      <td>Passwort Bestätigen:</td>
      <td><input type="password" name="bestätigung_passwort"></td>
    </tr>
    <tr>
      <td>PLZ:</td>
      <td><input type="number_format" name="plz"></td>
    </tr>
    <tr>
      <td>Ort:</td>
      <td><input type="text" name="ort"></td>
    </tr>
    <tr>
      <td>Strasse:</td>
      <td><input type="text" name="strasse"></td>
    </tr>
    <tr></tr>
      <td>Hausnummer:</td>
      <td><input type="text" name="hausnummer"></td>
    </tr>
    <tr>
      <td>Rolle:</td>
      <td><select name="Rolle">
        <option value="admin">Admin</option>
        <option value="user">User</option>
      </select></td>
    </tr>
<tfoot>
    <tr>
      <td colspan="2"><button style="margin-left: 150px;" class="btn" type="submit" name="action" value="benutzer_erstellen">Benutzer erstellen</button></td>
    </tr>
    </tfoot>
  </table>
  </form>
</details> 



<?php
$sql = "SELECT * FROM artikel";
$result = mysqli_query($conn, $sql);

$idArtikel = array();
$name = array();
$beschreibung = array();
$kategorie = array();
$pfad = array();
$preis = array();

while ($row = mysqli_fetch_assoc($result)) {
    $idArtikel[] = $row['idArtikel'];
    $name[] = $row['Artikel_name'];
    $beschreibung[] = $row['Artikel_beschreibung'];
    $kategorie[] = $row['kategorie_idkategorie'];
    $pfad[] = $row['Artikel_bildpfad'];
    $preis[] = $row['Artikel_preis'];
}
?>


    <h2>Artikeln verwalten</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Beschreibung</th>
                <th>Kategorie ID</th>
                <th>Bildpfad</th>
                <th>Preis</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $i < count($idArtikel); $i++)
              echo'  <tr>
                    <td><button style="background: none; border: none; cursor: pointer;" type="button" onclick="copyToClipboard(\'' . addslashes($idArtikel[$i]) . '\')"><span class="material-symbols-outlined">content_copy</span></button>' . htmlspecialchars($idArtikel[$i]) . '</td>
                    <td>' . htmlspecialchars($name[$i]) . '</td>
                    <td>' . htmlspecialchars($beschreibung[$i]) . '</td>
                    <td>' . htmlspecialchars($kategorie[$i]) . '</td>
                    <td>' . htmlspecialchars($pfad[$i]) . '</td>
                    <td>' . htmlspecialchars($preis[$i]) . '</td>
                </tr>';
             ?>
        </tbody>
    </table>

<br>





<details>
  <summary>Artikel Name Ändern<span class="material-symbols-outlined icon">arrow_downward</span></summary>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
  <table>
    <tr>
      <td>ID:</td>
      <td><input type="number_format" name="artikelID"></td>
    </tr>
    <tr>
      <td>Neuer Name:</td>
      <td><input type="text" name="artikelname"></td>
    </tr>
<tfoot>
    <tr>
      <td colspan="2"><button style="margin-left: 150px;" class="btn" type="submit" name="action" value="artikelname_form">Name Ändern</button></td>
    </tr>
    </tfoot>
  </table>
  </form>
</details> 




<details>
  <summary>Beschreibung Ändern<span class="material-symbols-outlined icon">arrow_downward</span></summary>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
  <table>
    <tr>
      <td>ID:</td>
      <td><input type="number_format" name="artikelID"></td>
    </tr>
    <tr>
      <td>Neue Beschreibung:</td>
      <td><input type="text" name="artikelbeschreibung"></td>
    </tr>
<tfoot>
    <tr>
      <td colspan="2"><button style="margin-left: 150px;" class="btn" type="submit" name="action" value="artikelbeschreibung_form">Beschreibung Ändern</button></td>
    </tr>
    </tfoot>
  </table>
  </form>
</details> 




<details>
  <summary>Kategorie ID Ändern<span class="material-symbols-outlined icon">arrow_downward</span></summary>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
  <table>
    <tr>
      <td>ID:</td>
      <td><input type="number_format" name="artikelID"></td>
    </tr>
    <tr>
      <td>Neue Kategorie ID:</td>
      <td><input type="text" name="artikelkid"></td>
    </tr>
<tfoot>
    <tr>
      <td colspan="2"><button style="margin-left: 150px;" class="btn" type="submit" name="action" value="artikelkid_form">Kategorie ID Ändern</button></td>
    </tr>
    </tfoot>
  </table>
  </form>
</details> 




<details>
  <summary>Bildpfad Ändern<span class="material-symbols-outlined icon">arrow_downward</span></summary>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
  <table>
    <tr>
      <td>ID:</td>
      <td><input type="number_format" name="artikelID"></td>
    </tr>
    <tr>
      <td>Neuer Artikel Bildpfad:</td>
      <td><input class type="file" name="artikelbildpfad"></td>
    </tr>
<tfoot>
    <tr>
      <td colspan="2"><button style="margin-left: 150px;" class="btn" type="submit" name="action" value="artikelbild_form">Bildpfad Ändern</button></td>
    </tr>
    </tfoot>
  </table>
  </form>
</details> 




<details>
  <summary>Preis Ändern<span class="material-symbols-outlined icon">arrow_downward</span></summary>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
  <table>
    <tr>
      <td>ID:</td>
      <td><input type="number_format" name="artikelID"></td>
    </tr>
    <tr>
      <td>Neuer Preis:</td>
      <td><input class type="file" name="artikelpris"></td>
    </tr>
<tfoot>
    <tr>
      <td colspan="2"><button style="margin-left: 150px;" class="btn" type="submit" name="action" value="artikelpreis_form">Preis Ändern</button></td>
    </tr>
    </tfoot>
  </table>
  </form>
</details> 



<details>
  <summary>Artikel Löschen<span class="material-symbols-outlined icon">arrow_downward</span></summary>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
  <table>
    <tr>
      <td>ID:</td>
      <td><input type="number_format" name="artikelID"></td>
    </tr>
<tfoot>
    <tr>
      <td colspan="2"><button style="margin-left: 150px;" class="btn" type="submit" name="action" value="artikellöschen">Artikel Löschen</button></td>
    </tr>
    </tfoot>
  </table>
  </form>
</details> 




<details>
  <summary>Artikel Erstellen<span class="material-symbols-outlined icon">arrow_downward</span></summary>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
  <table>
    <tr>
      <td>Name:</td>
      <td><input type="text" name="artikelname"></td>
    </tr>
    <tr>
      <td>Neue Beschreibung:</td>
      <td><input type="text" name="artikelbeschreibung"></td>
    </tr>
    <tr>
      <td>Neue Kategorie ID:</td>
      <td><input type="text" name="artikelkid"></td>  
    </tr>
    <tr>
      <td>Neuer Preis:</td>
      <td><input type="number" name="artikelpris" step="0.01" min="0"></td>
    </tr>
  
    <tr>
      <td>Neuer Artikel Bildpfad:</td>
      <td><input class type="file" name="artikelbildpfad"></td>
    </tr>
<tfoot>
    <tr>
      <td colspan="2"><button style="margin-left: 150px;" class="btn" type="submit" name="action" value="artikelerstellen_form">Artikel Erstellen</button></td>
    </tr>
    </tfoot>
  </table>
  </form>
</details> 

<?php
$sql = "SELECT * FROM kategorie";
$result = mysqli_query($conn, $sql);

$idKategorie = [];
$kategorie_name = [];
$kategorie_beschreibung = [];

// Suche diese Stelle (ca. Zeile 488) und ersetze sie durch:
while ($row = mysqli_fetch_assoc($result)) {
    $idKategorie[] = $row['idkategorie'];
    // Falls der Fehler bleibt, ändere 'Kategorie_name' zu 'kategorie_name'
    $kategorie_name[] = $row['kategorie_name']; 
    // Falls der Fehler bleibt, ändere 'Kategorie_beschreibung' zu 'kategorie_beschreibung'
    $kategorie_beschreibung[] = $row['kategorie_beschreibung'];
}
?>


    <h2>Kategorien verwalten</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Beschreibung</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $i < count($idKategorie); $i++)
              echo'  <tr>
                    <td><button style="background: none; border: none; cursor: pointer;" type="button" onclick="copyToClipboard(\'' . addslashes($idKategorie[$i]) . '\')"><span class="material-symbols-outlined">content_copy</span></button>' . htmlspecialchars($idKategorie[$i]) . '</td>
                    <td>' . htmlspecialchars($kategorie_name[$i]) . '</td>
                    <td>' . htmlspecialchars($kategorie_beschreibung[$i]) . '</td>
                </tr>';
             ?>
        </tbody>
    </table>

<br>
<details>
  <summary>kategorie Löschen<span class="material-symbols-outlined icon">arrow_downward</span></summary>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
  <table>
    <tr>
      <td>ID:</td>
      <td><input type="number_format" name="kategorieID"></td>
    </tr>
<tfoot>
    <tr>
      <td colspan="2"><button style="margin-left: 150px;" class="btn" type="submit" name="action" value="kategorielöschen">Kategorie Löschen</button></td>
    </tr>
    </tfoot>
  </table>
  </form>
</details> 

<details>
  <summary>kategorie Erstellen<span class="material-symbols-outlined icon">arrow_downward</span></summary>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
  <table>
    <tr>
      <td>Neue Name:</td>
      <td><input type="text" name="kategorie_name"></td>
    </tr>
    <tr>
      <td>Neue Beschreibung:</td>
      <td><input type="text" name="kategorie_beschreibung"></td>
    </tr>
<tfoot>
    <tr>
      <td colspan="2"><button style="margin-left: 150px;" class="btn" type="submit" name="action" value="kategorieerstellen">kategorie Erstellen</button></td>
    </tr>
    </tfoot>
  </table>
  </form>
</details> 


<details>
  <summary>kategorie Name Ändern<span class="material-symbols-outlined icon">arrow_downward</span></summary>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
  <table>
    <tr>
      <td>ID:</td>
      <td><input type="number_format" name="kategorieID"></td>
    </tr>
    <tr>
      <td>Neue Name:</td>
      <td><input type="text" name="kategorie_name"></td>
    </tr>
<tfoot>
    <tr>
      <td colspan="2"><button style="margin-left: 150px;" class="btn" type="submit" name="action" value="kategorie_name_form">kategorie Name Ändern</button></td>
    </tr>
    </tfoot>
  </table>
  </form>
</details> 


<details>
  <summary>kategorie Beschreibung Ändern<span class="material-symbols-outlined icon">arrow_downward</span></summary>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
  <table>
    <tr>
      <td>ID:</td>
      <td><input type="number_format" name="kategorieID"></td>
    </tr>
    <tr>
      <td>Neue Beschreibung:</td>
      <td><input type="text" name="kategorie_beschreibung"></td>
    </tr>
<tfoot>
    <tr>
      <td colspan="2"><button style="margin-left: 150px;" class="btn" type="submit" name="action" value="kategorie_beschreibung_form">kategorie Beschreibung Ändern</button></td>
    </tr>
    </tfoot>
  </table>
  </form>
</details> 

<?php
$sql = "SELECT * FROM lieferrant";
$result = mysqli_query($conn, $sql);

$idLieferrant = [];
$Lieferrant_name = [];
$Lieferrant_Telefonnummer = [];
$Lieferrant_email = [];

while ($row = mysqli_fetch_assoc($result)) {
    $idLieferrant[] = $row['idLieferrant'];
    $Lieferrant_name[] = $row['Lieferrant_name'];
    $Lieferrant_Telefonnummer[] = $row['Lieferrant_Telefonnummer'];
    $Lieferrant_email[] = $row['Lieferrant_email'];
}
?>


    <h2>Lieferrant verwalten</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Telefonnummer</th>
                <th>E-Mail</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $i < count($idLieferrant); $i++)
              echo'  <tr>
                    <td><button style="background: none; border: none; cursor: pointer;" type="button" onclick="copyToClipboard(\'' . addslashes($idLieferrant[$i]) . '\')"><span class="material-symbols-outlined">content_copy</span></button>' . htmlspecialchars($idLieferrant[$i]) . '</td>
                    <td>' . htmlspecialchars($Lieferrant_name[$i]) . '</td>
                    <td>' . htmlspecialchars($Lieferrant_Telefonnummer[$i]) . '</td>
                    <td>' . htmlspecialchars($Lieferrant_email[$i]) . '</td>
                </tr>';
             ?>
        </tbody>
    </table>

<br>
<details>
  <summary>Lieferrant Löschen<span class="material-symbols-outlined icon">arrow_downward</span></summary>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
  <table>
    <tr>
      <td>ID:</td>
      <td><input type="number_format" name="lieferrantID"></td>
    </tr>
<tfoot>
    <tr>
      <td colspan="2"><button style="margin-left: 150px;" class="btn" type="submit" name="action" value="lieferrantlöschen">Lieferrant Löschen</button></td>
    </tr>
    </tfoot>
  </table>
  </form>
</details> 

<details>
  <summary>Lieferrant Erstellen<span class="material-symbols-outlined icon">arrow_downward</span></summary>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
  <table>
    <tr>
      <td>Neue Name:</td>
      <td><input type="number_format" name="lieferrant_name"></td>
    </tr>
    <tr>
      <td>Neue Telefonnummer:</td>
      <td><input type="number_format" name="lieferrant_telefonnummer"></td>
    </tr>
    <tr>
      <td>Neue Email:</td>
      <td><input type="number_format" name="lieferrant_email"></td>
    </tr>
<tfoot>
    <tr>
      <td colspan="2"><button style="margin-left: 150px;" class="btn" type="submit" name="action" value="lieferranterstellen">Lieferrant Erstellen</button></td>
    </tr>
    </tfoot>
  </table>
  </form>
</details> 


<details>
  <summary>Lieferrant Name Ändern<span class="material-symbols-outlined icon">arrow_downward</span></summary>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
  <table>
    <tr>
      <td>ID:</td>
      <td><input type="number_format" name="lieferrantID"></td>
    </tr>
    <tr>
      <td>Neue Name:</td>
      <td><input type="number_format" name="lieferrant_name"></td>
    </tr>
<tfoot>
    <tr>
      <td colspan="2"><button style="margin-left: 150px;" class="btn" type="submit" name="action" value="lieferrant_name_form">Lieferrant Name Ändern</button></td>
    </tr>
    </tfoot>
  </table>
  </form>
</details> 


<details>
  <summary>Lieferrant Telefonnummer Ändern<span class="material-symbols-outlined icon">arrow_downward</span></summary>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
  <table>
    <tr>
      <td>ID:</td>
      <td><input type="number_format" name="lieferrantID"></td>
    </tr>
    <tr>
      <td>Neue Telefonnummer:</td>
      <td><input type="number_format" name="lieferrant_telefonnummer"></td>
    </tr>
<tfoot>
    <tr>
      <td colspan="2"><button style="margin-left: 150px;" class="btn" type="submit" name="action" value="lieferrant_telefonnummer_form">Lieferrant Telefonnummer Ändern</button></td>
    </tr>
    </tfoot>
  </table>
  </form>
</details> 

<details>
  <summary>Lieferrant E-Mail Ändern<span class="material-symbols-outlined icon">arrow_downward</span></summary>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
  <table>
    <tr>
      <td>ID:</td>
      <td><input type="number_format" name="lieferrantID"></td>
    </tr>
    <tr>
      <td>Neue E-Mail:</td>
      <td><input type="email" name="lieferrant_email"></td>
    </tr>
<tfoot>
    <tr>
      <td colspan="2"><button style="margin-left: 150px;" class="btn" type="submit" name="action" value="lieferrant_email_form">Lieferrant E-Mail Ändern</button></td>
    </tr>
    </tfoot>
  </table>
  </form>
</details> 

<script>
function copyToClipboard(text) {
  navigator.clipboard.writeText(text)
    .then(() => {
      // Rückmeldung an PHP durch Umleitung mit Query-Parameter
      window.location.href = window.location.pathname + '?copied=' + encodeURIComponent(text);
    })
    .catch(err => alert("Kopieren fehlgeschlagen: " + err));
}
    </script>
</body>
</html>