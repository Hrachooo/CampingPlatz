<?php
require_once '../php/db.php';

if (!isset($_GET['id'])) {
    die("Ungültige Benutzer-ID.");
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM benutzer WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("Benutzer nicht gefunden.");
}

$feedback = '';

// Änderungen speichern
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role_id = intval($_POST['role_id']);

    $update = $conn->prepare("UPDATE benutzer SET username=?, name=?, email=?, role_id=? WHERE id=?");
    $update->bind_param("sssii", $username, $name, $email, $role_id, $id);

    if ($update->execute()) {
        $feedback = '<div class="feedback success">Änderungen erfolgreich gespeichert!</div>';
        // Werte aktualisieren
        $user['username'] = $username;
        $user['name'] = $name;
        $user['email'] = $email;
        $user['role_id'] = $role_id;
    } else {
        $feedback = '<div class="feedback error">Fehler beim Speichern. Bitte prüfen Sie Ihre Eingaben.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Benutzer bearbeiten</title>
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

/* Form */
form {
    width: 90%;
    max-width: 500px;
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

form input, form select {
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
    transition: 0.2s;
}

form button:hover {
    background: #3b539d;
}

/* Feedback */
.feedback {
    width: 90%;
    max-width: 500px;
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
    <h1>Benutzer bearbeiten</h1>

    <?= $feedback ?>

    <form method="POST">
        <label>Username
            <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
        </label>

        <label>Name
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
        </label>

        <label>Email
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        </label>

        <label>Rolle
            <select name="role_id" required>
                <option value="1" <?= $user['role_id']==1 ? 'selected' : '' ?>>Admin</option>
                <option value="2" <?= $user['role_id']==2 ? 'selected' : '' ?>>Sachbearbeiter</option>
                <option value="3" <?= $user['role_id']==3 ? 'selected' : '' ?>>Geschäftsführer</option>
            </select>
        </label>

        <button type="submit">Speichern</button>
    </form>

    <a class="btn-back" href="benutzer.php">← Zurück zur Tabelle</a>
</div>

</body>
</html>
