<?php
require_once '../php/db.php';

  $result = $conn->query("
    SELECT stellplatz.*, buchung.stellplatz_id AS stellplatz_id
    FROM stellplatz
    LEFT JOIN buchung ON stellplatz.id = buchung.stellplatz_id
    ORDER BY (buchung.stellplatz_id IS NOT NULL) DESC, nummer ASC
");

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Stellplaetze</title>
    <style>
        table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px auto;
            font-family: Arial, sans-serif;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        h1 {
            text-align: center;
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body>

<?php include '../components/sidebar.php'; ?>

<div style="width: 100%; margin-left: 200px">
    <h1>Stellplaetze</h1>

    <table>
        <tr>
            <th>ID</th>
            <th>Nummer</th>
            <th>Groesse in qm</th>
            <th>Strom</th>
            <th>Wasser</th>
            <th>WC-Ausgestattet</th>
            <th>Info</th>
            <th>Preis</th>
            <th>Gebucht</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $gebucht = ($row['stellplatz_id'] != null) ? 'Ja' : 'Nein';
                echo "<tr>";
                echo "<td>".$row['id']."</td>";
                echo "<td>".$row['nummer']."</td>";
                echo "<td>".$row['qm']."</td>";
                echo "<td>".$row['strom']."</td>";
                echo "<td>".$row['wasser']."</td>";
                echo "<td>".$row['wc']."</td>";
                echo "<td>".$row['info']."</td>";
                echo "<td>".$row['preis']."</td>";
                echo "<td>".$gebucht."</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7' style='text-align:center;'>Kein Stellplatz gefunden</td></tr>";
        }
        ?>
    </table>
 </div>
</body>
</html>
