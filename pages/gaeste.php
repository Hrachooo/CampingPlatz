<?php
session_start();
require_once '../php/db.php';

// Zugriff verweigern, wenn kein Login vorhanden
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

$roleid = $_SESSION['roleid'];

// Zugriff verweigern, wenn Rolle ungleich 3
if ($roleid == 1) {
    ?>
    <!DOCTYPE html>
    <html lang="de">
    <head>
        <meta charset="UTF-8">
        <title>Zugriff verweigert</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: #f5f6fa;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .denied-box {
                background: #fff;
                padding: 30px;
                border-radius: 12px;
                box-shadow: 0 6px 20px rgba(0,0,0,0.1);
                text-align: center;
                max-width: 400px;
            }
            h1 {
                color: #c0392b;
                margin-bottom: 15px;
            }
            p {
                color: #555;
                margin-bottom: 20px;
            }
            a {
                display: inline-block;
                padding: 10px 18px;
                background: #3498db;
                color: #fff;
                text-decoration: none;
                border-radius: 6px;
            }
            a:hover {
                background: #2980b9;
            }
        </style>
    </head>
    <body>
        <div class="denied-box">
            <h1>Zugriff verweigert</h1>
            <p>Du hast leider keine Berechtigung, diese Seite zu sehen.</p>
            <a href="../login.php">Zurück zum Login</a>
        </div>
    </body>
    </html>
    <?php
    exit;
} else {
    // Gäste holen
    $result = $conn->query("
        SELECT g.*, a.strasse, a.hausnr, a.plz, a.ort
        FROM gast g
        LEFT JOIN anschrift a ON g.anschrift_id = a.id
    ");
}
?>


<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Gästeübersicht</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f6fa;
        }

        .content {
            margin-left: 230px; /* Sidebar-Breite */
            padding: 30px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        /* Suchfeld & Filter */
        .search-filter-box {
            width: 80%;
            margin: 10px auto 25px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .search-filter-box input,
        .search-filter-box select {
            padding: 10px;
            width: 48%;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        /* Tabelle */
        table {
            width: 90%;
            margin: 0 auto;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        th {
            background: #4a69bd;
            color: white;
            padding: 12px 10px;
            font-size: 15px;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }

        tr:hover {
            background: #f0f3f7;
        }

        /* Buttons */
        .btn-edit {
            background: #1e90ff;
            color: white;
            padding: 6px 10px;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 5px;
            font-size: 13px;
        }
        .btn-edit:hover {
            background: #0f67c5;
        }

        .btn-delete {
            background: #e74c3c;
            color: white;
            padding: 6px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 13px;
        }
        .btn-delete:hover {
            background: #c0392b;
        }

        .action-container {
            display: flex;
            gap: 6px;
            align-items: center;
        }
    </style>
</head>
<body>

<?php include '../components/sidebar.php'; ?>

<div class="content">

    <h1>Gästeliste</h1>

<!-- Suchfeld & Filter -->
<div class="search-filter-box" style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
    <input type="text" id="searchInput" placeholder="🔍 Suche nach Name, Vorname oder ID" 
           style="flex:1; min-width:300px; padding:10px; font-size:16px; border-radius:6px; border:1px solid #ccc;">
</div>

    <!-- Tabelle -->
    <table id="gastTable">
        <tr>
            <th>ID</th>
            <th>Nachname</th>
            <th>Vorname</th>
            <th>Adresse</th>
            <th>Email</th>
            <th>Telefon</th>
            <th>Geburtsdatum</th>
            <th>Aktion</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $adresse = htmlspecialchars($row['strasse'].' '.$row['hausnr'].', '.$row['plz'].' '.$row['ort']);
                echo "<tr>";
                echo "<td>".$row['id']."</td>";
                echo "<td>".$row['nachname']."</td>";
                echo "<td>".$row['vorname']."</td>";
                echo "<td>".$adresse."</td>";
                echo "<td>".$row['emal']."</td>";
                echo "<td>".$row['phone']."</td>";
                echo "<td>".$row['geburtsdatum']."</td>";
                echo "<td class='action-container'>
                        <a class='btn-edit' href='edit_gast.php?id=".$row['id']."'>Bearbeiten</a>
                        <form method='POST' action='delete_gast.php' onsubmit='return confirm(\"Möchten Sie diesen Gast wirklich löschen?\");'>
                            <input type='hidden' name='id' value='".$row['id']."'>
                            <button class='btn-delete' type='submit'>Löschen</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8' style='text-align:center;'>Kein Gast gefunden</td></tr>";
        }
        ?>
    </table>
</div>

<script>
/* LIVE-SUCHE */
document.getElementById("searchInput").addEventListener("keyup", function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("#gastTable tr:not(:first-child)");

    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
    });
});
</script>

</body>
</html>
