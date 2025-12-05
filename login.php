<?php
session_start();
require_once 'php/db.php'; // Deine DB-Verbindung

// Falls der Benutzer bereits eingeloggt ist, leite ihn zum Dashboard weiter
if (isset($_SESSION['role_id'])) {
    if ($_SESSION['role_id'] == 1)
    {
        header("Location: ./pages/benutzer.php");
    }
    else {
        header("Location: ./pages/gaeste.php");
    }
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

            if ($user['role_id'] == 1)
            {
                header("Location: ./pages/benutzer.php");
            }
            else {
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

    @keyframes rise{
      from { opacity: 0; transform: translateY(12px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .title{
      margin: 0 0 0.75rem;
      font-size: 1.6rem;
      font-weight: 700;
      text-align: center;
    }

    .subtitle{
      margin: 0 0 1.5rem;
      text-align: center;
      color: var(--muted);
      font-size: 0.95rem;
    }

    label{
      display: block;
      font-weight: 600;
      margin-bottom: 0.4rem;
    }

    .field{
      margin-bottom: 1rem;
    }

    .input{
      width: 100%;
      padding: 12px 12px;
      border: 1px solid #dfe6e9;
      border-radius: 10px;
      font-size: 15px;
      background: #fbfbfb;
      transition: border-color 0.2s, box-shadow 0.2s;
    }

    .input:focus{
      outline: none;
      border-color: var(--accent);
      box-shadow: 0 0 0 4px rgba(52,152,219,0.15);
      background: #fff;
    }

    .password-wrap{
      position: relative;
    }

    .toggle{
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: var(--accent);
      font-weight: 600;
      cursor: pointer;
      padding: 4px 6px;
    }

    .actions{
      margin-top: 1rem;
    }

    .btn{
      width: 100%;
      background: var(--accent);
      color: #fff;
      border: none;
      padding: 12px;
      font-size: 16px;
      border-radius: 10px;
      cursor: pointer;
      transition: background 0.25s, transform 0.15s;
    }

    .btn:hover{ background: var(--accent-dark); }
    .btn:active{ transform: translateY(1px); }

    .helper{
      text-align: center;
      margin-top: 0.9rem;
      color: var(--muted);
      font-size: 0.9rem;
    }

    .error{
      display: none; /* bei Bedarf per PHP/JS sichtbar machen */
      background: #fdecea;
      color: var(--error);
      padding: 10px 12px;
      border-radius: 8px;
      border: 1px solid #f5c6cb;
      margin-bottom: 1rem;
      font-size: 0.95rem;
    }
  </style>
</head>
<body>

    <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <div class="card">
    <h1 class="title">Anmeldung</h1>
    <p class="subtitle">Bitte melde dich mit deinem Benutzerkonto an.</p>

    <div class="error" id="errorBox">Ungültiger Benutzername oder Passwort.</div>

    <form action="login.php" method="POST" novalidate>
      <div class="field">
        <label for="username">Benutzername</label>
        <input class="input" type="text" id="username" name="username" autocomplete="username" required />
      </div>

      <div class="field password-wrap">
        <label for="password">Passwort</label>
        <input class="input" type="password" id="password" name="password" autocomplete="current-password" required />
        <button type="button" class="toggle" aria-label="Passwort anzeigen" onclick="togglePw()">anzeigen</button>
      </div>

      <div class="actions">
        <button class="btn" type="submit">Login</button>
      </div>

      <p class="helper">Probleme beim Login? Wende dich an den Administrator.</p>
    </form>
  </div>

  <script>
    function togglePw(){
      const pw = document.getElementById('password');
      const btn = event.target;
      if(pw.type === 'password'){
        pw.type = 'text';
        btn.textContent = 'verbergen';
      }else{
        pw.type = 'password';
        btn.textContent = 'anzeigen';
      }
    }
  </script>
</body>
</html>
