<?php
require_once '../php/db.php';

$result = $conn->query("
    SELECT benutzer.*, role.type as role_type
    FROM benutzer
    LEFT JOIN role ON benutzer.role_id = role.id"
);

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Benutzer</title>
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
      <?php include './new_user.php'; ?>
    </div>
  
    <h1>Benutzerliste</h1>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Password</th>
            <th>Role</th>
            <th>Name</th>
            <th>Email</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row['id']."</td>";
                echo "<td>".$row['username']."</td>";
                echo "<td>".$row['password']."</td>";
                echo "<td>".$row['role_type']."</td>";
                echo "<td>".$row['name']."</td>";
                echo "<td>".$row['email']."</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7' style='text-align:center;'>Kein Benutzer gefunden</td></tr>";
        }
        ?>
    </table>

</div>

<script>
   
    const modal = document.getElementById('userModal');
    const openBtn = document.getElementById('openModalBtn');
    const closeBtn = document.getElementById('closeModal');

    openBtn.onclick = () => modal.style.display = 'flex';
    closeBtn.onclick = () => modal.style.display = 'none';

   
    window.onclick = (e) => {
        if (e.target === modal) modal.style.display = 'none';
    }
</script>
</body>
</html>
