<?php
require_once '../php/db.php';

$result = $conn->query("
    SELECT stellplatz.*, buchung.stellplatz_id AS gebucht_stellplatz_id
    FROM stellplatz
    LEFT JOIN buchung ON stellplatz.id = buchung.stellplatz_id
    ORDER BY (buchung.stellplatz_id IS NOT NULL) DESC, nummer ASC
");
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Stellplätze</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f5f6fa;
        }

        table {
            width: 90%;
            border-collapse: separate; /* statt collapse, damit border-radius wirkt */
            border-spacing: 0; /* keine Lücken zwischen Zellen */
            margin: 0 auto;
            background: white;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            border-radius: 10px; /* abgerundete Ecken */
            overflow: hidden; /* verhindert, dass Inhalte über die Rundungen hinausgehen */
        }

        th, td {
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid #ccc; /* Trenner zwischen Zeilen */
        }

        th:first-child {
            border-top-left-radius: 10px;
        }

        th:last-child {
            border-top-right-radius: 10px;
        }

        tr:last-child td:first-child {
            border-bottom-left-radius: 10px;
        }

        tr:last-child td:last-child {
            border-bottom-right-radius: 10px;
        }

        h1 {
            text-align: center;
            margin-top: 30px;
        }

        .content {
            margin-left: 230px;
            padding: 30px;
        }

        .filter-box {
            width: 90%;
            margin: 10px auto 25px auto;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-box label {
            font-weight: bold;
            margin-right: 5px;
        }

        .filter-box input,
        .filter-box select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        table {
            width: 90%;
            border-collapse: collapse;
            margin: 0 auto;
            background: white;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px 12px;
            text-align: left;
        }

        th {
            background-color: #4a69bd;
            color: white;
        }

        tr:hover {
            background-color: #f0f3f7;
        }
    </style>
</head>
<body>

<?php include '../components/sidebar.php'; ?>

<div class="content">
    <h1>Stellplätze</h1>

<div class="filter-box">
    <input type="text" id="searchInput" placeholder="🔍 Suchen nach Nummer oder Info..." 
           style="flex:1; min-width:300px; padding:10px; font-size:16px; border-radius:6px; border:1px solid #ccc;">

    <label for="filterStrom">Strom:</label>
    <select id="filterStrom">
        <option value="">Alle</option>
        <option value="Ja">Ja</option>
        <option value="Nein">Nein</option>
    </select>

    <label for="filterWasser">Wasser:</label>
    <select id="filterWasser">
        <option value="">Alle</option>
        <option value="Ja">Ja</option>
        <option value="Nein">Nein</option>
    </select>

    <label for="filterWC">WC:</label>
    <select id="filterWC">
        <option value="">Alle</option>
        <option value="Ja">Ja</option>
        <option value="Nein">Nein</option>
    </select>

    <label for="filterGebucht">Gebucht:</label>
    <select id="filterGebucht">
        <option value="">Alle</option>
        <option value="Ja">Ja</option>
        <option value="Nein">Nein</option>
    </select>
</div>


    <table id="stellplatzTable">
        <tr>
            <th>ID</th>
            <th>Nummer</th>
            <th>Größe (qm)</th>
            <th>Strom</th>
            <th>Wasser</th>
            <th>WC ausgestattet</th>
            <th>Info</th>
            <th>Preis</th>
            <th>Gebucht</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $gebucht = ($row['gebucht_stellplatz_id'] != null) ? 'Ja' : 'Nein';
                echo "<tr>";
                echo "<td>".$row['id']."</td>";
                echo "<td>".$row['nummer']."</td>";
                echo "<td>".$row['qm']."</td>";
                echo "<td>".($row['strom'] ? 'Ja' : 'Nein')."</td>";
                echo "<td>".($row['wasser'] ? 'Ja' : 'Nein')."</td>";
                echo "<td>".($row['wc'] ? 'Ja' : 'Nein')."</td>";
                echo "<td>".$row['info']."</td>";
                echo "<td>".$row['preis']."</td>";
                echo "<td>".$gebucht."</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='9' style='text-align:center;'>Kein Stellplatz gefunden</td></tr>";
        }
        ?>
    </table>
</div>

<script>
const searchInput = document.getElementById('searchInput');
const filterStrom = document.getElementById('filterStrom');
const filterWasser = document.getElementById('filterWasser');
const filterWC = document.getElementById('filterWC');
const filterGebucht = document.getElementById('filterGebucht');

function filterTable() {
    const rows = document.querySelectorAll('#stellplatzTable tr:not(:first-child)');
    rows.forEach(row => {
        const nummerInfo = (row.cells[1].textContent + ' ' + row.cells[6].textContent).toLowerCase();
        const strom = row.cells[3].textContent;
        const wasser = row.cells[4].textContent;
        const wc = row.cells[5].textContent;
        const gebucht = row.cells[8].textContent;

        let show = true;
        if (searchInput.value && !nummerInfo.includes(searchInput.value.toLowerCase())) show = false;
        if (filterStrom.value && strom !== filterStrom.value) show = false;
        if (filterWasser.value && wasser !== filterWasser.value) show = false;
        if (filterWC.value && wc !== filterWC.value) show = false;
        if (filterGebucht.value && gebucht !== filterGebucht.value) show = false;

        row.style.display = show ? '' : 'none';
    });
}

searchInput.addEventListener('keyup', filterTable);
filterStrom.addEventListener('change', filterTable);
filterWasser.addEventListener('change', filterTable);
filterWC.addEventListener('change', filterTable);
filterGebucht.addEventListener('change', filterTable);
</script>

</body>
</html>
