<?php
session_start();
require_once 'php/db.php'; // Deine DB-Verbindung

// Falls der Benutzer bereits eingeloggt ist, leite ihn zum Dashboard weiter
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

// Variablen für Fehlermeldungen
$error_message = "";

// Wenn das Formular abgesendet wurde
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Benutzereingaben holen
    $username = $_POST['username'];
    $password = $_POST['password'];

    // SQL-Abfrage vorbereiten
    $stmt = $conn->prepare("SELECT * FROM benutzer WHERE username = '$username'");
    //$stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Benutzer existiert
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Passwort überprüfen
        if ($password == $user['password']) {
            // Erfolgreich eingeloggt, Session setzen
            $_SESSION['username'] = $user['username'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['roleid'] = $user['role_id'];



            // Weiterleitung zum Dashboard oder geschützte Seite
            header("Location: dashboard.php");
            exit();
        } else {
            $error_message = "Falsches Passwort!";
        }
    } else {
        $error_message = "Benutzername nicht gefunden!";
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>

    <!-- Wenn ein Fehler aufgetreten ist, zeige die Fehlermeldung -->
    <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <label for="username">Benutzername:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Passwort:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Login">
    </form>
</body>
</html>
