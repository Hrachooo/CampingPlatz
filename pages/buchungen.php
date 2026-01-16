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
}

// BUCHUNGEN + ABRECHNUNG LADEN
$result = $conn->query("
    SELECT 
        b.*, 
        g.vorname, 
        g.nachname,
        a.gesamt_betrag,
        a.created_at AS abrechnung_datum
    FROM buchung b
    LEFT JOIN gast g ON b.gast_id = g.id
    LEFT JOIN abrechnung a ON a.buchung_id = b.id
");
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Buchungen</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f6fa;
        }
        .content {
            margin-left: 230px;
            padding: 30px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .search-box {
            width: 80%;
            margin: 10px auto 25px auto;
        }
        .search-box input {
            padding: 10px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }
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
    </style>
</head>
<body>

<?php include '../components/sidebar.php'; ?>

<div class="content">
    <h1>Buchungsliste</h1>

    <!-- Suchfeld -->
    <div class="search-box">
        <input type="text" id="searchInput" placeholder="🔍 Suchen nach Gast, Stellplatz oder ID...">
    </div>

    <!-- Tabelle -->
    <table id="buchungTable">
        <tr>
            <th>ID</th>
            <th>Gast</th>
            <th>Gast ID</th>
            <th>Stellplatz ID</th>
            <th>Anreise</th>
            <th>Abreise</th>
            <th>Strom</th>
            <th>Tiere</th>
            <th>Erwachsene</th>
            <th>Kinder</th>
            <th>Gesamtpreis</th>
            <th>Abrechnung erstellt</th>
            <th>Buchung erstellt</th>
        </tr>

        <?php
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {

                $gastName = htmlspecialchars($row['vorname'].' '.$row['nachname']);
                $preis = $row['gesamt_betrag'] !== null 
                    ? number_format($row['gesamt_betrag'], 2, ",", ".") . " €"
                    : "<span style='color:#e74c3c;'>Keine Abrechnung</span>";

                echo "<tr>";
                echo "<td>".$row['id']."</td>";
                echo "<td>".$gastName."</td>";
                echo "<td>".$row['gast_id']."</td>";
                echo "<td>".$row['stellplatz_id']."</td>";
                echo "<td>".$row['anreise_datum']."</td>";
                echo "<td>".$row['abreise_datum']."</td>";
                echo "<td>".($row['strom'] ? "Ja" : "Nein")."</td>";
                echo "<td>".($row['tiere'] ? "Ja" : "Nein")."</td>";
                echo "<td>".$row['anzahl_erwachsene']."</td>";
                echo "<td>".$row['anzahl_kinder']."</td>";
                echo "<td>".$preis."</td>";
                echo "<td>".$row['abrechnung_datum']."</td>";
                echo "<td>".$row['created_at']."</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='13' style='text-align:center;'>Keine Buchungen gefunden</td></tr>";
        }
        ?>
    </table>
</div>

<script>
document.getElementById("searchInput").addEventListener("keyup", function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("#buchungTable tr:not(:first-child)");

    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
    });
});
</script>

</body>
</html>
