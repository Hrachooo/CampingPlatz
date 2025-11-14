<?php
require_once '../php/db.php';

if (!isset($_GET['id'])) {
    die("Ungültige Benutzer-ID.");
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM Benutzer WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("Benutzer nicht gefunden.");
}

//save changes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role_id = $_POST['role_id'];

    $update = $conn->prepare("UPDATE benutzer SET username=?, name=?, email=?, role_id=? WHERE id=?");
    $update->bind_param("sssii", $username, $name, $email, $role_id, $id);
    $update->execute();

    header("Location: benutzer.php");
    exit;
}
?>

<form method="POST">
    <label>Username: <input type="text" name="username" value="<?= $user['username'] ?>"></label><br>
    <label>Name: <input type="text" name="name" value="<?= $user['name'] ?>"></label><br>
    <label>Email: <input type="email" name="email" value="<?= $user['email'] ?>"></label><br>
    <label>Role: 
        <select name="role_id">
            <option value="1" <?= $user['role_id']==1?'selected':'' ?>>Admin</option>
            <option value="2" <?= $user['role_id']==2?'selected':'' ?>>Sachbearbeiter</option>
            <option value="2" <?= $user['role_id']==3?'selected':'' ?>>Geschäftsführer</option>
        </select>
    </label><br>
    <button type="submit">Speichern</button>
</form>
