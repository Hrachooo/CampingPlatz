<?php
require_once '../php/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $anreise = $_POST['anreise_datum'];
    $abreise = $_POST['abreise_datum'];
    $qm      = floatval($_POST['qm']);
    $wasser  = isset($_POST['wasser']) ? 1 : 0;
    $strom   = isset($_POST['strom']) ? 1 : 0;

    // passende Stellplätze suchen
    $sql = "SELECT * FROM stellplatz 
            WHERE qm >= ? AND wasser >= ? AND strom >= ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("dii", $qm, $wasser, $strom);
    $stmt->execute();
    $result = $stmt->get_result();

    $freiePlaetze = [];

    while ($platz = $result->fetch_assoc()) {
        // prüfen ob Platz im Zeitraum schon gebucht ist
        $check = $conn->prepare("SELECT COUNT(*) FROM buchung 
                                 WHERE stellplatz_id = ? 
                                 AND (anreise_datum <= ? AND abreise_datum >= ?)");
        $check->bind_param("iss", $platz['id'], $abreise, $anreise);
        $check->execute();
        $anzahl = $check->get_result()->fetch_row()[0];

        if ($anzahl == 0) {
            $freiePlaetze[] = $platz;
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="de">
    <head>
        <meta charset="UTF-8">
        <title>Freie Stellplaetze</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f6f8;
                margin: 0;
                padding: 2rem;
            }
            h2 {
                text-align: center;
                color: #2c3e50;
            }
            .platz-container {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 1.5rem;
                margin-top: 2rem;
            }
            .platz-card {
                background: #fff;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                padding: 1.5rem;
                text-align: center;
                transition: transform 0.2s ease;
            }
            .platz-card:hover {
                transform: translateY(-5px);
            }
            .platz-info {
                font-size: 1.1rem;
                margin-bottom: 1rem;
                color: #34495e;
            }
            .platz-btn {
                background-color: #27ae60;
                color: #fff;
                border: none;
                padding: 10px 20px;
                border-radius: 6px;
                cursor: pointer;
                font-size: 14px;
                transition: background-color 0.3s ease;
            }
            .platz-btn:hover {
                background-color: #1e8449;
            }
            .no-result {
                text-align: center;
                font-size: 1.2rem;
                color: #c0392b;
                margin-top: 2rem;
            }
        </style>
    </head>
    <body>
    <?php
    if (count($freiePlaetze) > 0) {
        echo "<h2>Freie Stellplaetze</h2>";
        echo "<div class='platz-container'>";
        foreach ($freiePlaetze as $p) {
            echo "<div class='platz-card'>
                    <div class='platz-info'>
                        <strong>Platz Nr. {$p['nummer']}</strong><br>
                        Groesse: {$p['qm']} qm<br>
                        Wasser: " . ($p['wasser'] ? "Ja" : "Nein") . "<br>
                        Strom: " . ($p['strom'] ? "Ja" : "Nein") . "
                    </div>
                    <form action='gastdaten.php' method='post'>
                        <input type='hidden' name='stellplatz_id' value='{$p['id']}'>
                        <input type='hidden' name='anreise_datum' value='$anreise'>
                        <input type='hidden' name='abreise_datum' value='$abreise'>
                        <button type='submit' class='platz-btn'>Diesen Platz waehlen</button>
                    </form>
                  </div>";
        }
        echo "</div>";
    } else {
        echo "<div class='no-result'>Leider keine freien Stellplaetze gefunden.</div>";
    }
    ?>
    </body>
    </html>
    <?php
}
?>
