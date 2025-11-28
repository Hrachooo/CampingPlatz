<?php
require_once '../php/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nachname = $_POST['nachname'];
    $vorname = $_POST['vorname'];
    $emal = $_POST['emal'];
    $phone = $_POST['phone'];
    $geburtsdatum = $_POST['geburtsdatum'];


    $strasse = $_POST['strasse'];
    $hausnr = $_POST['hausnr'];
    $plz = $_POST['plz'];
    $ort = $_POST['ort'];

    $stmt = $conn->prepare("INSERT INTO anschrift (strasse, hausnr, plz, ort) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $strasse, $hausnr, $plz, $ort);

    if ($stmt->execute()) {
        $anschrift_id = $conn->insert_id;
    } else {
        echo "<script>alert('Fehler beim Erstellen der Adresse'); window.history.back();</script>";
    }

    $stmt = $conn->prepare("INSERT INTO gast (nachname, vorname, anschrift_id, emal, phone, geburtsdatum) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nachname, $vorname, $anschrift_id, $emal, $phone, $geburtsdatum);

    if ($stmt->execute()) {
        echo "<script>alert('Gast erfolgreich erstellt'); window.location.href='gaeste.php';</script>";
    } else {
        echo "<script>alert('Fehler beim Erstellen des Gastes'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
