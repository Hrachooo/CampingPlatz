<?php
require_once '../php/db.php';

$feedback = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $role     = intval($_POST['role']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("INSERT INTO benutzer (username, password, role_id, name, email) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $username, $password, $role, $name, $email);

    if ($stmt->execute()) {
        $feedback = '<div class="feedback success">Benutzer erfolgreich erstellt!</div>';
    } else {
        $feedback = '<div class="feedback error">Fehler beim Erstellen des Benutzers: ' . htmlspecialchars($stmt->error) . '</div>';
    }

    $stmt->close();
    $conn->close();
}
?>

<!-- Feedback-Anzeige -->
<?= $feedback ?>

<!-- Optional: Weiterleitung nach 2 Sekunden -->
<?php if ($feedback && strpos($feedback, 'erfolgreich') !== false): ?>
<script>
    setTimeout(() => {
        window.location.href = 'benutzer.php';
    }, 2000); // 2 Sekunden warten
</script>
<?php endif; ?>

<style>
.feedback {
    max-width: 600px;
    margin: 10px auto;
    padding: 12px 15px;
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
</style>
