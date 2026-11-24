<?php
$link = mysqli_connect("localhost", "root", "", "mydb", 3306);

if (isset($_POST['idArtikel'])) {
    $id = intval($_POST['idArtikel']);
    $name = $_POST['name'] ?? '';
    $preis = floatval($_POST['preis'] ?? 0);
    $bildpfad = $_POST['bildpfad'] ?? '';
    $iduser = intval($_POST['userid'] ?? 0);

    // 1. Prüfen, ob der Artikel heute schon existiert
    $sql_check = "SELECT idbestellung, menge FROM bestellung WHERE Artikel_idArtikel = $id AND datum = CURDATE()";
    $result = mysqli_query($link, $sql_check);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        // 2. Wenn ja: Menge um 1 erhöhen (UPDATE)
        $neueMenge = $row['menge'] + 1;
        $idBestellung = $row['idbestellung'];
        $sql = "UPDATE bestellung SET menge = $neueMenge WHERE idbestellung = $idBestellung";
    } else {
        // 3. Wenn nein: Neu anlegen (INSERT)
        // Spalten in der Reihenfolge wie in der Tabelle, ohne abschließendes Komma
        $sql = "INSERT INTO bestellung (
                    Artikel_idArtikel,
                    user_id,
                    menge,
                    kategorieid,
                    datum,
                    name,
                    preis,
                    bildpfad
                ) VALUES (
                    $id,
                    $iduser,
                    1,
                    1,
                    CURDATE(),
                    '" . mysqli_real_escape_string($link, $name) . "',
                    $preis,
                    '" . mysqli_real_escape_string($link, $bildpfad) . "'
                )";
    }
    
    if(mysqli_query($link, $sql)) {
        echo "Erfolg";
    } else {
        echo "Fehler: " . mysqli_error($link);
    }
}

?>