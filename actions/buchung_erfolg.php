<?php
require_once '../php/db.php';

// Prüfen, ob eine Buchungs-ID mitgegeben wurde
$buchung_id = $_GET['buchung_id'] ?? null;

if (!$buchung_id) {
    die("<p style='color:red;font-family:Arial;'>Keine gültige Buchungs-ID übergeben.</p>");
}

// Buchung aus Datenbank laden
$stmt = $conn->prepare("
    SELECT b.*, g.vorname, g.nachname
    FROM buchung b
    JOIN gast g ON b.gast_id = g.id
    WHERE b.id = ?
");
$stmt->bind_param("i", $buchung_id);
$stmt->execute();
$buchung = $stmt->get_result()->fetch_assoc();

if (!$buchung) {
    die("<p style='color:red;font-family:Arial;'>Buchung wurde nicht gefunden.</p>");
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Buchung erfolgreich</title>
<style>
    body {
        background: #f2f4f8;
        font-family: Arial, sans-serif;
        padding: 40px 10px;
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: flex-start;
    }

    .card {
        background: white;
        padding: 25px 35px;
        max-width: 600px;
        width: 100%;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        text-align: center;
        animation: fadein 0.3s ease-out;
    }

    @keyframes fadein {
        from {opacity: 0; transform: translateY(10px);}
        to {opacity: 1; transform: translateY(0);}
    }

    h1 {
        margin-top: 0;
        color: #2a7a2e;
        font-size: 28px;
    }

    .buchungsnummer {
        font-size: 30px;
        font-weight: bold;
        margin: 15px 0 25px;
        color: #155d18;
    }

    .details {
        text-align: left;
        margin-top: 20px;
        line-height: 1.6;
        font-size: 16px;
        color: #444;
    }

    .btn {
        display: inline-block;
        margin-top: 30px;
        padding: 12px 25px;
        background: #1e73be;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-size: 16px;
        transition: 0.2s;
    }

    .btn:hover {
        background: #155d8a;
    }
</style>
</head>
<body>

<div class="card">
    <h1>Buchung erfolgreich!</h1>

    <div class="buchungsnummer">
        Buchungsnummer: <?= htmlspecialchars($buchung_id) ?>
    </div>

    <p>Vielen Dank fuer Ihre Buchung, <strong><?= htmlspecialchars($buchung['vorname']) . " " . htmlspecialchars($buchung['nachname']) ?></strong>!</p>

    <div class="details">
        <p><strong>Anreise:</strong> <?= htmlspecialchars($buchung['anreise_datum']) ?></p>
        <p><strong>Abreise:</strong> <?= htmlspecialchars($buchung['abreise_datum']) ?></p>
        <p><strong>Erwachsene:</strong> <?= (int)$buchung['anzahl_erwachsene'] ?></p>
        <p><strong>Kinder:</strong> <?= (int)$buchung['anzahl_kinder'] ?></p>
        <p><strong>Strom:</strong> <?= $buchung['strom'] ? "Ja" : "Nein" ?></p>
        <p><strong>Haustiere:</strong> <?= $buchung['tiere'] ? "Ja" : "Nein" ?></p>
        <p><strong>Stellplatz ID:</strong> <?= (int)$buchung['stellplatz_id'] ?></p>
    </div>

    <a class="btn" href="../index.php">Zurueck zur Startseite</a>
</div>

</body>
</html>
