<?php
require_once '../php/db.php';

// Gäste holen
$result = $conn->query("SELECT id, nachname, vorname, anschrift_id, emal, phone, geburtsdatum FROM gast");
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Gaesteuebersicht</title>
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
    <div style="margin-top: 20px">
      <?php include './new_gast.php'; ?>
    </div>

    <h1>Gaesteliste</h1>

    <table>
        <tr>
            <th>ID</th>
            <th>Nachname</th>
            <th>Vorname</th>
            <th>Anschrift-ID</th>
            <th>Email</th>
            <th>Telefon</th>
            <th>Geburtsdatum</th>
            <th>Aktion</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row['id']."</td>";
                echo "<td>".$row['nachname']."</td>";
                echo "<td>".$row['vorname']."</td>";
                echo "<td>".$row['anschrift_id']."</td>";
                echo "<td>".$row['emal']."</td>";
                echo "<td>".$row['phone']."</td>";
                echo "<td>".$row['geburtsdatum']."</td>";
                echo "<td>
                        <a href='edit_gast.php?id=".$row['id']."' style='margin-right:5px;'>Bearbeiten</a>
                        <form method='POST' action='delete_gast.php' onsubmit='return confirm(\"Möchten Sie diesen Gast wirklich löschen?\");'>
                            <input type='hidden' name='id' value='".$row['id']."'>
                            <button type='submit'>Loeschen</button>
                        </form>
                    </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7' style='text-align:center;'>Kein Gast gefunden</td></tr>";
        }
        ?>
    </table>
 </div>


 <script>
   
    const modal = document.getElementById('gastModal');
    const openBtn = document.getElementById('openGastModalBtn');
    const closeBtn = document.getElementById('closeGastModal');

    openBtn.onclick = () => modal.style.display = 'flex';
    closeBtn.onclick = () => modal.style.display = 'none';

   
    window.onclick = (e) => {
        if (e.target === modal) modal.style.display = 'none';
    }
</script>
</body>
</html>
