<?php
require_once '../php/db.php';

$result = $conn->query("
    SELECT benutzer.*, role.type AS role_type
    FROM benutzer
    LEFT JOIN role ON benutzer.role_id = role.id
");
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Benutzerverwaltung</title>
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

        /* Such- und Filterbox */
        .filter-box {
            width: 80%;
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
            width: 80%;
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

    <div style="margin-bottom: 20px;">
        <?php include './new_user.php'; ?>
    </div>

    <h1>Benutzerliste</h1>

<!-- Such- und Filterbox -->
<div class="filter-box">
    <input type="text" id="searchInput" placeholder="🔍 Suchen nach Name, E-Mail oder Username ..." style="flex:1; min-width:300px; font-size:16px; padding:10px;">

    <label for="filterRole">Rolle:</label>
    <select id="filterRole" style="padding:8px; font-size:14px;">
        <option value="">Alle Rollen</option>
        <?php
        $roles = $conn->query("SELECT DISTINCT type FROM role");
        while($r = $roles->fetch_assoc()) {
            echo '<option value="'.$r['type'].'">'.$r['type'].'</option>';
        }
        ?>
    </select>
</div>


    <table id="userTable">
        <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Rolle</th>
            <th>Name</th>
            <th>Email</th>
            <th>Aktion</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row['id']."</td>";
                echo "<td>".$row['username']."</td>";
                echo "<td>".$row['role_type']."</td>";
                echo "<td>".$row['name']."</td>";
                echo "<td>".$row['email']."</td>";
                echo "<td class='action-container'>
                        <a class='btn-edit' href='edit_user.php?id=".$row['id']."'>Bearbeiten</a>
                        <form method='POST' action='delete_user.php' onsubmit='return confirm(\"Möchten Sie diesen Benutzer wirklich löschen?\");'>
                            <input type='hidden' name='id' value='".$row['id']."'>
                            <button class='btn-delete' type='submit'>Löschen</button>
                        </form>
                    </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7' style='text-align:center;'>Keine Benutzer gefunden</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<script>
const searchInput = document.getElementById('searchInput');
const filterRole = document.getElementById('filterRole');

function filterTable() {
    const rows = document.querySelectorAll('#userTable tbody tr');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const role = row.cells[3].textContent;
        const name = row.cells[4].textContent.toLowerCase();

        let show = true;
        if (searchInput.value && !text.includes(searchInput.value.toLowerCase())) show = false;
        if (filterRole.value && role !== filterRole.value) show = false;

        row.style.display = show ? '' : 'none';
    });
}

searchInput.addEventListener('keyup', filterTable);
filterRole.addEventListener('change', filterTable);
</script>

</body>
</html>
