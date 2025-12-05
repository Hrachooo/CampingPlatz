<?php
require_once '../php/db.php';

// Buchungen holen
$result = $conn->query("
    SELECT b.*, g.vorname, g.nachname
    FROM buchung b
    LEFT JOIN gast g ON b.gast_id = g.id
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
            margin-left: 230px; /* Sidebar-Breite */
            padding: 30px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        /* Suchfeld */
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
            <th>Erstellt am</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $gastName = htmlspecialchars($row['vorname'].' '.$row['nachname']);
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
                echo "<td>".$row['created_at']."</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='11' style='text-align:center;'>Keine Buchungen gefunden</td></tr>";
        }
        ?>
    </table>
</div>

<script>
/* LIVE-SUCHE */
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
