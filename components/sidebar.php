<!-- sidebar.php -->
<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        display: flex;
    }

    /* Sidebar */
    .sidebar {
        width: 180px;
        background-color: #2c3e50;
        height: 100vh;
        color: white;
        display: flex;
        flex-direction: column;
        padding-top: 20px;
        position: fixed;
    }

    .sidebar h2 {
        text-align: center;
        margin-bottom: 30px;
        font-size: 20px;
        color: #ecf0f1;
    }

    .sidebar a {
        text-decoration: none;
        color: #ecf0f1;
        padding: 12px 20px;
        display: block;
        transition: 0.3s;
    }

    .sidebar a:hover {
        background-color: #34495e;
        border-left: 4px solid #1abc9c;
    }

    /* Main content */
    .main-content {
        margin-left: 220px;
        padding: 20px;
        flex-grow: 1;
    }
</style>

<div class="sidebar">
    <h2>Campingplatz</h2>
    <a href="./../pages/gaeste.php">Gaeste</a>
    <a href="./../pages/buchungen.php">Buchungen</a>
    <a href="./../pages/stellplaetze.php">Stellplaetze</a>
    <a href="./../pages/benutzer.php">Benutzer</a>

</div>
