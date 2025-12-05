<?php
$id = $_GET['buchung_id'] ?? null;
?>
<h2>Buchung erfolgreich!</h2>
<p>Ihre Buchungsnummer: <strong><?= htmlspecialchars($id) ?></strong></p>
