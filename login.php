<?php
session_start();
require_once 'php/db.php'; // DB-Verbindung

// Falls der Benutzer bereits eingeloggt ist, leite ihn zum Dashboard weiter
if (isset($_SESSION['roleid'])) {
    if ($_SESSION['roleid'] == 1) {
        header("Location: ./pages/benutzer.php");
    } else {
        header("Location: ./pages/gaeste.php");
    }
    exit();
}

$error_message = "";

// Wenn das Formular abgesendet wurde
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // SQL-Abfrage mit Prepared Statement
    $stmt = $conn->prepare("SELECT * FROM benutzer WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Passwort prüfen (Hash in DB!)
        if (password_verify($password, $user['password'])) {
            // Session setzen
            $_SESSION['username'] = $user['username'];
            $_SESSION['name']     = $user['name'];
            $_SESSION['roleid']   = $user['role_id'];

            // Session-ID erneuern (Sicherheit)
            session_regenerate_id(true);

            // Weiterleiten je nach Rolle
            if ($user['role_id'] == 1) {
                header("Location: ./pages/benutzer.php");
            } else {
                header("Location: ./pages/gaeste.php");
            }
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
    <style>
        :root{
          --bg: #f4f6f8;
          --card: #ffffff;
          --accent: #3498db;
          --accent-dark: #2980b9;
          --text: #2c3e50;
          --muted: #7f8c8d;
          --error: #c0392b;
        }
        *{ box-sizing: border-box; }
        body{
          margin: 0;
          min-height: 100vh;
          display: grid;
          place-items: center;
          background: linear-gradient(135deg, #e8f0ff 0%, #f4f6f8 60%);
          font-family: "Segoe UI", Tahoma, sans-serif;
          color: var(--text);
          padding: 1.5rem;
        }
        .card{
          width: 100%;
          max-width: 420px;
          background: var(--card);
          border-radius: 14px;
          box-shadow: 0 12px 24px rgba(0,0,0,0.12);
          padding: 2rem;
          animation: rise 0.5s ease;
        }
        h2{ margin-top:0; text-align:center; }
        .form-group{ margin-bottom:1rem; }
        label{ display:block; margin-bottom:0.5rem; }
        input{
          width:100%; padding:0.75rem;
          border:1px solid #ccc; border-radius:8px;
        }
        button{
          width:100%; padding:0.75rem;
          background: #1e2a38;
          border:none; border-radius:8px;
          font-size:1rem; cursor:pointer;
          color: white;
        }
        button:hover{ background:var(--accent-dark); }
        .error{ color:var(--error); text-align:center; margin-top:1rem; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Login</h2>
        <?php if (!empty($error_message)): ?>
            <div class="error"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Benutzername</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Passwort</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Einloggen</button>
        </form>
    </div>
</body>
</html>
