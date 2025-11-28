<?php
require_once '../php/db.php';

// Daten aus GET
$gast_id      = $_GET['gast_id'];
$stellplatz_id= $_GET['stellplatz_id'];
$anreise      = $_GET['anreise_datum'];
$abreise      = $_GET['abreise_datum'];
$erwachsene   = intval($_GET['erwachsene']);
$kinder       = intval($_GET['kinder']);
$tier         = $_GET['tier'];
$wasser       = $_GET['wasser'];
$strom        = $_GET['strom'];

// Gastdaten laden
$stmt = $conn->prepare("SELECT g.nachname, g.vorname, g.emal, g.phone, g.geburtsdatum, a.strasse, a.hausnr, a.plz, a.ort 
                        FROM gast g 
                        JOIN anschrift a ON g.anschrift_id = a.id 
                        WHERE g.id = ?");
$stmt->bind_param("i", $gast_id);
$stmt->execute();
$gast = $stmt->get_result()->fetch_assoc();

// Preis berechnen (Beispiel)
$preis = $erwachsene * 20 + $kinder * 10;
if ($tier)   $preis += 5;
if ($wasser) $preis += 3;
if ($strom)  $preis += 4;
$tage = (strtotime($abreise) - strtotime($anreise)) / (60*60*24);
if ($tage < 1) $tage = 1;
$preis *= $tage;
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Buchungsübersicht</title>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, sans-serif;
        background: linear-gradient(135deg, #74ebd5 0%, #ACB6E5 100%);
        margin: 0;
        padding: 2rem;
        display: flex;
        justify-content: center;
    }
    .card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 6px 16px rgba(0,0,0,0.15);
        padding: 2rem;
        width: 600px;
        animation: fadeIn 0.6s ease;
    }
    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(20px);}
        to {opacity: 1; transform: translateY(0);}
    }
    h2 {
        text-align: center;
        color: #2c3e50;
        margin-bottom: 1.5rem;
    }
    .section {
        margin-bottom: 1.5rem;
    }
    .section h3 {
        margin-bottom: 0.5rem;
        color: #34495e;
        border-bottom: 1px solid #eee;
        padding-bottom: 0.3rem;
    }
    .item {
        margin: 0.3rem 0;
        color: #555;
    }
    .price {
        font-size: 1.4rem;
        font-weight: bold;
        color: #27ae60;
        text-align: center;
        margin-top: 1rem;
    }
    button {
        width: 100%;
        background-color: #e74c3c;
        color: #fff;
        border: none;
        padding: 14px;
        font-size: 16px;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
        margin-top: 1.5rem;
    }
    button:hover {
        background-color: #c0392b;
        transform: translateY(-2px);
    }
</style>
</head>
<body>
<div class="card">
    <h2>Buchungsuebersicht</h2>

    <div class="section">
        <h3>Gastdaten</h3>
        <p class="item"><strong>Gast:</strong> <?= htmlspecialchars($gast['vorname']." ".$gast['nachname']) ?></p>
        <p class="item"><strong>Adresse:</strong> <?= htmlspecialchars($gast['strasse']." ".$gast['hausnr'].", ".$gast['plz']." ".$gast['ort']) ?></p>
        <p class="item"><strong>Email:</strong> <?= htmlspecialchars($gast['emal']) ?></p>
        <p class="item"><strong>Telefon:</strong> <?= htmlspecialchars($gast['phone']) ?></p>
        <p class="item"><strong>Geburtsdatum:</strong> <?= htmlspecialchars($gast['geburtsdatum']) ?></p>
    </div>

    <div class="section">
        <h3>Buchungsdetails</h3>
        <p class="item"><strong>Anreise:</strong> <?= htmlspecialchars($anreise) ?></p>
        <p class="item"><strong>Abreise:</strong> <?= htmlspecialchars($abreise) ?></p>
        <p class="item"><strong>Erwachsene:</strong> <?= $erwachsene ?></p>
        <p class="item"><strong>Kinder:</strong> <?= $kinder ?></p>
        <p class="item"><strong>Haustier:</strong> <?= $tier ? "Ja" : "Nein" ?></p>
        <p class="item"><strong>Wasser:</strong> <?= $wasser ? "Ja" : "Nein" ?></p>
        <p class="item"><strong>Strom:</strong> <?= $strom ? "Ja" : "Nein" ?></p>
        <p class="item"><strong>Stellplatz-ID:</strong> <?= $stellplatz_id ?></p>
    </div>

    <div class="price">Gesamtpreis: <?= number_format($preis,2,",",".") ?> Euro</div>

    <form method="POST" action="finalize_buchung.php">
        <input type="hidden" name="gast_id" value="<?= $gast_id ?>">
        <input type="hidden" name="stellplatz_id" value="<?= $stellplatz_id ?>">
        <input type="hidden" name="anreise_datum" value="<?= $anreise ?>">
        <input type="hidden" name="abreise_datum" value="<?= $abreise ?>">
        <input type="hidden" name="erwachsene" value="<?= $erwachsene ?>">
        <input type="hidden" name="kinder" value="<?= $kinder ?>">
        <input type="hidden" name="tier" value="<?= $tier ?>">
        <input type="hidden" name="wasser" value="<?= $wasser ?>">
        <input type="hidden" name="strom" value="<?= $strom ?>">
        <input type="hidden" name="preis" value="<?= $preis ?>">
        <button type="submit">Kostenpflichtig buchen</button>
    </form>
</div>
</body>
</html>
