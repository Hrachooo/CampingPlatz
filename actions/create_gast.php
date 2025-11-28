<?php
require_once '../php/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $strasse = $_POST['strasse'];
    $hausnr = $_POST['hausnr'];
    $plz = $_POST['plz'];
    $ort = $_POST['ort'];

    $stmt = $conn->prepare("INSERT INTO anschrift (strasse, hausnr, plz, ort) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $strasse, $hausnr, $plz, $ort);

    if ($stmt->execute()) {
        $anschrift_id = $conn->insert_id;
    } else {
        die("<script>alert('Fehler beim Erstellen der Adresse'); window.history.back();</script>");
    }

    $nachname     = $_POST['nachname'];
    $vorname      = $_POST['vorname'];
    $emal         = $_POST['emal'];
    $phone        = $_POST['phone'];
    $geburtsdatum = $_POST['geburtsdatum'];

    $stmt = $conn->prepare("INSERT INTO gast (nachname, vorname, anschrift_id, emal, phone, geburtsdatum) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nachname, $vorname, $anschrift_id, $emal, $phone, $geburtsdatum);

    if ($stmt->execute()) {
        $gast_id = $conn->insert_id;
        // Weiterleitung zur Buchungsübersicht mit allen Daten
        header("Location: buchung_uebersicht.php?gast_id=$gast_id&stellplatz_id={$_POST['stellplatz_id']}&anreise_datum={$_POST['anreise_datum']}&abreise_datum={$_POST['abreise_datum']}&erwachsene={$_POST['anzahl_erwachsene']}&kinder={$_POST['anzahl_kinder']}&tier={$_POST['tier']}&wasser={$_POST['wasser']}&strom={$_POST['strom']}");
        exit;
    } else {
        echo "<script>alert('Fehler beim Erstellen des Gastes'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
