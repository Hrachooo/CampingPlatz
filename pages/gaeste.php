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
                margin: 0;
                font-family: 'Inter', Arial, sans-serif;
                background: #f0f2f5; /* modernes hellgrau */
                color: #2d3436;
            }

            /* Content */
            .content {
                margin-left: 230px;
                padding: 40px;
            }

            /* Titel */
            h1 {
                text-align: center;
                color: #1e293b; /* modernes dunkelblau-grau */
                font-weight: 600;
                margin-bottom: 25px;
            }

            /* Suchfeld */
            .search-filter-box input {
                padding: 12px;
                border: 1px solid #d1d5db;
                border-radius: 8px;
                font-size: 15px;
                background: #fff;
                transition: 0.2s;
            }

            .search-filter-box input:focus {
                border-color: #6366f1; /* Indigo */
                box-shadow: 0 0 0 3px rgba(99,102,241,0.2);
                outline: none;
            }

            /* Tabelle */
            table {
                width: 90%;
                margin: 0 auto;
                border-collapse: collapse;
                background: #ffffff;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            }

            th {
                background: #6366f1; /* Indigo */
                color: white;
                padding: 14px 12px;
                font-size: 15px;
                font-weight: 500;
            }

            td {
                padding: 12px;
                border-bottom: 1px solid #e5e7eb;
                font-size: 14px;
                color: #374151;
            }

            tr:hover {
                background: #f9fafb;
            }

            /* Buttons */
            .btn-edit {
                background: #3b82f6; /* modernes Blau */
                color: white;
                padding: 7px 12px;
                text-decoration: none;
                border-radius: 6px;
                font-size: 13px;
                transition: 0.2s;
            }

            .btn-edit:hover {
                background: #2563eb;
            }

            .btn-delete {
                background: #ef4444; /* modernes Rot */
                color: white;
                padding: 7px 12px;
                border: none;
                border-radius: 6px;
                cursor: pointer;
                font-size: 13px;
                transition: 0.2s;
            }

            .btn-delete:hover {
                background: #dc2626;
            }

            .action-container {
                display: flex;
                gap: 8px;
                align-items: center;
            }

            /* Zugriff verweigert Box */
            .denied-box {
                background: #ffffff;
                padding: 35px;
                border-radius: 14px;
                box-shadow: 0 6px 25px rgba(0,0,0,0.08);
                text-align: center;
            }

            .denied-box h1 {
                color: #ef4444;
            }

            .denied-box a {
                background: #6366f1;
                padding: 10px 20px;
                border-radius: 8px;
                color: white;
                text-decoration: none;
                transition: 0.2s;
            }

            .denied-box a:hover {
                background: #4f46e5;
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
            background: #1e2a38;
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
            background: #0b3c91;
            color: white;
            padding: 6px 10px;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 5px;
            font-size: 13px;
        }
        .btn-edit:hover {
            background: #1e88e5;
        }

        .btn-delete {
            background: #b11226;
            color: white;
            padding: 6px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 13px;
        }
        .btn-delete:hover {
            background: #8b1e3f;
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
