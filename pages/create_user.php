<?php
require_once '../php/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = intval($_POST['role']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("INSERT INTO benutzer (username, password, role_id, name, email) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $password, $role, $name, $email);

    if ($stmt->execute()) {
        echo "<script>alert('User erfolgreich erstellt'); window.location.href='benutzer.php';</script>";
    } else {
        echo "<script>alert('Fehler beim Erstellen des Users'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
