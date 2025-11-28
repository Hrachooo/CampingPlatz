<?php
// Erwartete Werte aus dem vorherigen Schritt
$stellplatz_id  = $_POST['stellplatz_id']  ?? null;
$anreise        = $_POST['anreise_datum']  ?? null;
$abreise        = $_POST['abreise_datum']  ?? null;
$erwachsene     = $_POST['erwachsene']     ?? null;
$kinder         = $_POST['kinder']         ?? null;
$tier           = isset($_POST['tier']) ? $_POST['tier'] : null;   // 0/1 oder ja/nein
$wasser         = isset($_POST['wasser']) ? $_POST['wasser'] : null; // 0/1
$strom          = isset($_POST['strom']) ? $_POST['strom'] : null;   // 0/1

// Einfache Validierung – wenn etwas Kritisches fehlt, abbrechen
if (!$stellplatz_id || !$anreise || !$abreise) {
    echo "<p style='color:#c0392b;font-family:Arial;'>Fehlende Daten. Bitte wählen Sie den Stellplatz erneut.</p>";
    echo "<p><a href='../route/index.php'>Zurück zur Suche</a></p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Gast erstellen</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 2rem;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }
        .form-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 2rem;
            width: 480px;
        }
        .form-card h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        .summary {
            background: #f8fbff;
            border: 1px solid #e3e9ef;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.25rem;
            color: #34495e;
            font-size: 14px;
        }
        .summary-item { margin: 0.25rem 0; }
        label {
            display: block;
            margin-bottom: 0.8rem;
            font-weight: bold;
            color: #34495e;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-top: 0.3rem;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }
        input:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 6px rgba(52,152,219,0.25);
        }
        button {
            width: 100%;
            background-color: #3498db;
            color: #fff;
            border: none;
            padding: 12px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin-top: 1rem;
        }
        button:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="form-card">
        <h2>Gastdaten eingeben</h2>

        <!-- Zusammenfassung der übernommenen Daten -->
        <div class="summary">
            <div class="summary-item"><strong>Anreise:</strong> <?php echo htmlspecialchars($anreise); ?></div>
            <div class="summary-item"><strong>Abreise:</strong> <?php echo htmlspecialchars($abreise); ?></div>
            <?php if ($erwachsene !== null): ?>
                <div class="summary-item"><strong>Erwachsene:</strong> <?php echo htmlspecialchars($erwachsene); ?></div>
            <?php endif; ?>
            <?php if ($kinder !== null): ?>
                <div class="summary-item"><strong>Kinder:</strong> <?php echo htmlspecialchars($kinder); ?></div>
            <?php endif; ?>
            <?php if ($tier !== null): ?>
                <div class="summary-item"><strong>Haustier:</strong> <?php echo ($tier ? 'Ja' : 'Nein'); ?></div>
            <?php endif; ?>
            <?php if ($wasser !== null): ?>
                <div class="summary-item"><strong>Wasser:</strong> <?php echo ($wasser ? 'Ja' : 'Nein'); ?></div>
            <?php endif; ?>
            <?php if ($strom !== null): ?>
                <div class="summary-item"><strong>Strom:</strong> <?php echo ($strom ? 'Ja' : 'Nein'); ?></div>
            <?php endif; ?>
        </div>

        <!-- Gastformular mit versteckten Feldern für die Buchung -->
        <form method="POST" action="./create_gast.php">
            <!-- Versteckte Felder: Übergabe aller vorherigen Daten -->
            <input type="hidden" name="stellplatz_id"  value="<?php echo htmlspecialchars($stellplatz_id); ?>">
            <input type="hidden" name="anreise_datum"  value="<?php echo htmlspecialchars($anreise); ?>">
            <input type="hidden" name="abreise_datum"  value="<?php echo htmlspecialchars($abreise); ?>">
            <?php if ($erwachsene !== null): ?>
                <input type="hidden" name="erwachsene" value="<?php echo htmlspecialchars($erwachsene); ?>">
            <?php endif; ?>
            <?php if ($kinder !== null): ?>
                <input type="hidden" name="kinder" value="<?php echo htmlspecialchars($kinder); ?>">
            <?php endif; ?>
            <?php if ($tier !== null): ?>
                <input type="hidden" name="tier" value="<?php echo htmlspecialchars($tier); ?>">
            <?php endif; ?>
            <?php if ($wasser !== null): ?>
                <input type="hidden" name="wasser" value="<?php echo htmlspecialchars($wasser); ?>">
            <?php endif; ?>
            <?php if ($strom !== null): ?>
                <input type="hidden" name="strom" value="<?php echo htmlspecialchars($strom); ?>">
            <?php endif; ?>

            <!-- Sichtbare Felder: Hauptgast -->
            <label>Nachname:
                <input type="text" name="nachname" required>
            </label>
            <label>Vorname:
                <input type="text" name="vorname" required>
            </label>
            <label>Strasse:
                <input type="text" name="strasse" required>
            </label>
            <label>Hausnummer:
                <input type="text" name="hausnr" required>
            </label>
            <label>PLZ:
                <input type="text" name="plz" required>
            </label>
            <label>Ort:
                <input type="text" name="ort" required>
            </label>
            <label>Email:
                <input type="email" name="emal" required>
            </label>
            <label>Telefon:
                <input type="text" name="phone" required>
            </label>
            <label>Geburtsdatum:
                <input type="date" name="geburtsdatum" required>
            </label>

            <button type="submit">Stellplatz reservieren</button>
        </form>
    </div>
</body>
</html>
