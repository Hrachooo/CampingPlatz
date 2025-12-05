<?php
require_once '../php/db.php';

$feedback = '';

// Prüfen, ob POST und ID gesetzt sind
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    $stmt = $conn->prepare("DELETE FROM benutzer WHERE id = ?");
    $stmt->bind_param("i", $id);

    try {
        if ($stmt->execute()) {
            $feedback = '<div class="feedback success">Benutzer erfolgreich gelöscht.</div>';
        } else {
            $feedback = '<div class="feedback error">Fehler beim Löschen: ' . htmlspecialchars($stmt->error) . '</div>';
        }
    } catch (mysqli_sql_exception $e) {
        // Fehler z.B. bei Foreign-Key-Constraint
        $feedback = '<div class="feedback error">Löschen fehlgeschlagen: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
    $stmt->close();
} else {
    $feedback = '<div class="feedback error">Ungültige Anfrage.</div>';
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Benutzer löschen</title>
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

/* Feedback */
.feedback {
    width: 90%;
    max-width: 500px;
    margin: 20px auto;
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

/* Zurück-Button */
.btn-back {
    display: block;
    width: 90%;
    max-width: 500px;
    margin: 15px auto;
    text-align: center;
    padding: 10px 0;
    background: #7f8c8d;
    color: white;
    border-radius: 6px;
    text-decoration: none;
    transition: 0.2s;
}

.btn-back:hover {
    background: #636e72;
}
</style>
</head>
<body>

<?php include '../components/sidebar.php'; ?>

<div class="content">
    <h1>Benutzer löschen</h1>

    <?= $feedback ?>

    <a class="btn-back" href="benutzer.php">← Zurück zur Benutzerübersicht</a>
</div>
</body>
</html>
