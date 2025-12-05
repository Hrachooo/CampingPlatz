<?php
require_once '../php/db.php';

if (!isset($_GET['id'])) {
    die("Ungültige Gast-ID.");
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT gast.*, anschrift.* FROM gast, anschrift WHERE gast.id = ? AND gast.anschrift_id = anschrift.id");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("Benutzer nicht gefunden.");
}

$feedback = ''; // Rückmeldung

// Änderungen speichern
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nachname = $_POST['nachname'];
    $vorname = $_POST['vorname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $geburtsdatum = $_POST['geburtsdatum'];

    $updateGast = $conn->prepare("UPDATE gast SET nachname=?, vorname=?, emal=?, phone=?, geburtsdatum=? WHERE id=?");
    $updateGast->bind_param("ssssss", $nachname, $vorname, $email, $phone, $geburtsdatum, $id);

    $strasse = $_POST['strasse'];
    $hausnr = $_POST['hausnr'];
    $plz = $_POST['plz'];
    $ort = $_POST['ort'];
    $anschrift_id = $user['anschrift_id'];

    $updateAdresse = $conn->prepare("UPDATE anschrift SET strasse=?, hausnr=?, plz=?, ort=? WHERE id=?");
    $updateAdresse->bind_param("sssss", $strasse, $hausnr, $plz, $ort, $anschrift_id);

    // Ausführen und Rückmeldung setzen
    if ($updateGast->execute() && $updateAdresse->execute()) {
        $feedback = '<div class="feedback success">Änderungen erfolgreich gespeichert!</div>';
    } else {
        $feedback = '<div class="feedback error">Fehler beim Speichern. Bitte prüfen Sie Ihre Eingaben.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Gast bearbeiten</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f5f6fa;
        margin: 0;
        padding: 0;
    }

    .content {
        margin-left: 230px;
        padding: 30px;
    }

    h1 {
        text-align: center;
        color: #333;
    }

    form {
        width: 90%;
        max-width: 600px;
        margin: 20px auto;
        background: #fff;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }

    form label {
        display: block;
        margin-bottom: 15px;
        font-weight: bold;
        color: #333;
    }

    form input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        margin-top: 5px;
    }

    form button {
        background: #4a69bd;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        margin-top: 10px;
        width: 100%;
    }

    form button:hover {
        background: #3b539d;
    }

    /* Feedback */
    .feedback {
        width: 90%;
        max-width: 600px;
        margin: 10px auto;
        padding: 12px 15px;
        border-radius: 6px;
        font-weight: bold;
        text-align: center;
    }

    .feedback.success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .feedback.error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    /* Zurück Button */
    .back-button {
        display: block;
        width: 90%;
        max-width: 600px;
        margin: 20px auto;
        text-align: center;
    }

    .back-button a {
        display: inline-block;
        background: #6c757d;
        color: white;
        padding: 10px 20px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: bold;
        margin-top: 10px;
    }

    .back-button a:hover {
        background: #5a6268;
    }
</style>
</head>
<body>

<?php include '../components/sidebar.php'; ?>

<div class="content">
    <h1>Gast bearbeiten</h1>

    <!-- Feedback-Meldung -->
    <?= $feedback ?>

    <form method="POST">
        <label>Nachname
            <input type="text" name="nachname" value="<?= htmlspecialchars($user['nachname']) ?>" required>
        </label>

        <label>Vorname
            <input type="text" name="vorname" value="<?= htmlspecialchars($user['vorname']) ?>" required>
        </label>

        <label>Strasse
            <input type="text" name="strasse" value="<?= htmlspecialchars($user['strasse']) ?>" required>
        </label>

        <label>Hausnummer
            <input type="text" name="hausnr" value="<?= htmlspecialchars($user['hausnr']) ?>" required>
        </label>

        <label>PLZ
            <input type="text" name="plz" value="<?= htmlspecialchars($user['plz']) ?>" required>
        </label>

        <label>Ort
            <input type="text" name="ort" value="<?= htmlspecialchars($user['ort']) ?>" required>
        </label>

        <label>Email
            <input type="email" name="email" value="<?= htmlspecialchars($user['emal']) ?>" required>
        </label>

        <label>Telefon
            <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>">
        </label>

        <label>Geburtsdatum
            <input type="date" name="geburtsdatum" value="<?= htmlspecialchars($user['geburtsdatum']) ?>">
        </label>

        <button type="submit">Speichern</button>
    </form>

    <!-- Zurück Button -->
    <div class="back-button">
        <a href="gaeste.php">Zurück zur Gästeliste</a>
    </div>
</div>

</body>
</html>
