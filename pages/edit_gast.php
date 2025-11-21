<?php
require_once '../php/db.php';

if (!isset($_GET['id'])) {
    die("Ungültige Gast-ID.");
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT gast.*, anschrift.* FROM gast, anschrift WHERE gast.id = ? AND gast.anschrift_id = anschrift.id");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("Benutzer nicht gefunden.");
}

//save changes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nachname = $_POST['nachname'];
    $vorname = $_POST['vorname'];
    $emal = $_POST['emal'];
    $phone = $_POST['phone'];
    $geburtsdatum = $_POST['geburtsdatum'];

    $update = $conn->prepare("UPDATE gast SET nachname=?, vorname=?, emal=?, phone=?, geburtsdatum=? WHERE id=?");
    $update->bind_param("ssssss", $nachname, $vorname, $emal, $phone, $geburtsdatum, $id);
    $update->execute();

    $strasse = $_POST['strasse'];
    $hausnr = $_POST['hausnr'];
    $plz = $_POST['plz'];
    $ort = $_POST['ort'];
    $anschrift_id = $user['anschrift_id'];

    $update = $conn->prepare("UPDATE anschrift SET strasse=?, hausnr=?, plz=?, ort=? WHERE id=?");
    $update->bind_param("sssss", $strasse, $hausnr, $plz, $ort, $anschrift_id);
    $update->execute();

    header("Location: gaeste.php");
    exit;
}
?>

<form method="POST">
    <label>Nachname: <input type="text" name="nachname" value="<?= $user['nachname'] ?>"></label><br>
    <label>Vorname: <input type="text" name="vorname" value="<?= $user['vorname'] ?>"></label><br>

    <label>Strasse: <input type="text" name="strasse" value="<?= $user['strasse'] ?>"></label><br>
    <label>Hausnummer: <input type="text" name="hausnr" value="<?= $user['hausnr'] ?>"></label><br>
    <label>PLZ: <input type="text" name="plz" value="<?= $user['plz'] ?>"></label><br>
    <label>Ort: <input type="text" name="ort" value="<?= $user['ort'] ?>"></label><br>

    <label>Email: <input type="email" name="emal" value="<?= $user['emal'] ?>"></label><br>
    <label>Telefon: <input type="text" name="phone" value="<?= $user['phone'] ?>"></label><br>
    <label>Geburtsdatum: <input type="date" name="geburtsdatum" value="<?= $user['geburtsdatum'] ?>"></label><br>
    <button type="submit">Speichern</button>
</form>
