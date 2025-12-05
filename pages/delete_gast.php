<?php
require_once '../php/db.php';

$feedback = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Prüfen, ob Buchungen existieren
    $check = $conn->prepare("SELECT COUNT(*) AS cnt FROM buchung WHERE gast_id = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $res = $check->get_result()->fetch_assoc();

    if ($res['cnt'] > 0) {
        // Gast hat Buchungen – löschen nicht möglich
        $feedback = '<div class="feedback error">Dieser Gast kann nicht gelöscht werden, da noch Buchungen bestehen.</div>';
    } else {
        // Gast löschen
        $stmt = $conn->prepare("DELETE FROM gast WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $feedback = '<div class="feedback success">Gast erfolgreich gelöscht!</div>';
        } else {
            $feedback = '<div class="feedback error">Fehler beim Löschen: ' . htmlspecialchars($stmt->error) . '</div>';
        }
    }
} else {
    $feedback = '<div class="feedback error">Ungültige Anfrage.</div>';
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Gast löschen</title>
<style>
.feedback {
    width: 90%;
    max-width: 600px;
    margin: 50px auto;
    padding: 15px;
    border-radius: 6px;
    text-align: center;
    font-weight: bold;
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

.back-button {
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
}

.back-button a:hover {
    background: #5a6268;
}
</style>
</head>
<body>

<?= $feedback ?>

<div class="back-button">
    <a href="gaeste.php">Zurück zur Gästeliste</a>
</div>

</body>
</html>
