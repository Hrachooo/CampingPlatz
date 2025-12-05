<?php
session_start();
if (!isset($_SESSION['roleid'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
/* Sidebar Container */
body {
    font-family: Arial, sans-serif;
}
.sidebar {
    width: 230px;
    background: #1e2a38;
    height: 100vh;
    color: white;
    display: flex;
    flex-direction: column;
    padding-top: 25px;
    position: fixed;
    box-shadow: 3px 0 10px rgba(0,0,0,0.25);
}

/* Header */
.sidebar h2 {
    text-align: center;
    font-size: 20px;
    color: #ecf0f1;
    letter-spacing: 1px;
    margin-bottom: 35px;
}

/* Menu Links */
.sidebar a {
    text-decoration: none;
    color: #d7e1ec;
    padding: 14px 22px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 15px;
    transition: 0.25s;
    border-left: 4px solid transparent;
}

/* Hover */
.sidebar a:hover {
    background: #243548;
    border-left: 4px solid #1abc9c;
}

/* Active state */
.sidebar a.active {
    background: #1f3347;
    border-left: 4px solid #1abc9c;
    color: #ffffff;
}

/* Icons */
.sidebar i {
    width: 20px;
    text-align: center;
    font-size: 17px;
}

/* Main Content Offset */
.main-content {
    margin-left: 230px;
    padding: 25px;
}

/* Responsive */
@media (max-width: 700px) {
    .sidebar {
        width: 180px;
    }
    .main-content {
        margin-left: 180px;
    }
}
</style>
</head>
<body>

<div class="sidebar">
    <h2><i class="fa-solid fa-campground"></i> Campingplatz</h2>

    <?php 
        $current = basename($_SERVER['PHP_SELF']);

        function active($page, $current) {
            return $page === $current ? "active" : "";
        }

        // Admin
        if ($_SESSION['roleid'] == 1) {
            echo '<a class="'.active("benutzer.php", $current).'" href="./../pages/benutzer.php">
                    <i class="fa-solid fa-users"></i> Benutzer
                  </a>';
        }

        // Mitarbeiter / Standard-Rolle
        else {
            echo '<a class="'.active("gaeste.php", $current).'" href="./../pages/gaeste.php">
                    <i class="fa-solid fa-user"></i> Gäste
                  </a>';

            echo '<a class="'.active("buchungen.php", $current).'" href="./../pages/buchungen.php">
                    <i class="fa-solid fa-calendar-check"></i> Buchungen
                  </a>';

            echo '<a class="'.active("stellplaetze.php", $current).'" href="./../pages/stellplaetze.php">
                    <i class="fa-solid fa-map"></i> Stellplätze
                  </a>';
        }
    ?>

    <a href="./../logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
</div>
</body>
</html>
