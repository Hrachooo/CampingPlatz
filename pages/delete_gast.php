<?php
require_once '../php/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    $stmt = $conn->prepare("DELETE FROM gast WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: gaeste.php");
        exit;
    } else {
        echo "Fehler beim Löschen: " . $stmt->error;
    }
} else {
    echo "Ungültige Anfrage.";
}
?>
