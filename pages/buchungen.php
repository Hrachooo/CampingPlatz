<?php
require_once '../php/db.php';

// Gäste holen

$result = $conn->query("SELECT id, gast_id, stellplatz_id, abreise_datum, anreise_datum, strom, tiere, anzahl_erwachsene, anzahl_kinder, created_at FROM buchung");
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Buchungen</title>
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
    <h1>Buchungsliste</h1>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Gast id</th>
            <th>Stellplatz id</th>
            <th>Abreise Datum</th>
            <th>Anreise Datum</th>
            <th>Strom</th>
            <th>Tiere</th>
            <th>Anzahl Erwachsene</th>
            <th>Anzahl Kinder</th>
            <th>Erstellt am</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row['id']."</td>";
                echo "<td>".$row['gast_id']."</td>";
                echo "<td>".$row['stellplatz_id']."</td>";
                echo "<td>".$row['abreise_datum']."</td>";
                echo "<td>".$row['anreise_datum']."</td>";
                echo "<td>".$row['strom']."</td>";
                echo "<td>".$row['tiere']."</td>";
                 echo "<td>".$row['anzahl_erwachsene']."</td>";
                echo "<td>".$row['anzahl_kinder']."</td>";
                echo "<td>".$row['created_at']."</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7' style='text-align:center;'>Keine Buchung gefunden</td></tr>";
        }
        ?>
    </table>

</div>
</body>
</html>
