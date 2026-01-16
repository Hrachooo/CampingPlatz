<?php
require_once '../php/db.php';

// Prüfen, ob das Formular per POST gesendet wurde
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Ungültiger Zugriff.");
}

// Pflichtfelder prüfen
$required = [
    'gast_id', 'stellplatz_id', 'anreise_datum', 'abreise_datum',
    'erwachsene', 'kinder', 'tier', 'wasser', 'strom', 'preis'
];

foreach ($required as $field) {
    if (!isset($_POST[$field])) {
        die("Fehlender Wert: $field");
    }
}

// Daten sichern
$gast_id        = intval($_POST['gast_id']);
$stellplatz_id  = intval($_POST['stellplatz_id']);
$anreise        = $_POST['anreise_datum'];
$abreise        = $_POST['abreise_datum'];
$erwachsene     = intval($_POST['erwachsene']);
$kinder         = intval($_POST['kinder']);
$tiere          = !empty($_POST['tier']) ? 1 : 0;
$strom          = !empty($_POST['strom']) ? 1 : 0;
$wasser         = !empty($_POST['wasser']) ? 1 : 0;
$gesamtpreis    = floatval($_POST['preis']);
$created_at     = date("Y-m-d H:i:s");

// Datums-Validierung
if (strtotime($anreise) === false || strtotime($abreise) === false) {
    die("Ungültiges Datum.");
}

if ($abreise < $anreise) {
    die("Abreisedatum darf nicht vor dem Anreisedatum liegen.");
}

// BUCHUNG speichern
$stmt = $conn->prepare("
    INSERT INTO buchung 
        (gast_id, stellplatz_id, abreise_datum, anreise_datum, strom, tiere, anzahl_erwachsene, anzahl_kinder, created_at) 
    VALUES 
        (?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "iissiiiss",
    $gast_id,
    $stellplatz_id,
    $abreise,
    $anreise,
    $strom,
    $tiere,
    $erwachsene,
    $kinder,
    $created_at
);

if (!$stmt->execute()) {
    die("Fehler beim Speichern der Buchung: " . $stmt->error);
}

$buchung_id = $stmt->insert_id;

// ABRECHNUNG speichern
$stmt2 = $conn->prepare("
    INSERT INTO abrechnung (buchung_id, created_at, gesamt_betrag)
    VALUES (?, ?, ?)
");

$stmt2->bind_param("isd", $buchung_id, $created_at, $gesamtpreis);

if (!$stmt2->execute()) {
    die("Fehler beim Speichern der Abrechnung: " . $stmt2->error);
}

// Weiterleitung
header("Location: buchung_erfolg.php?buchung_id=" . $buchung_id);
exit;

?>
