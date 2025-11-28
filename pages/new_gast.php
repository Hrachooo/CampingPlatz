<button id="openGastModalBtn">Neuen Gast erstellen</button>

<div id="gastModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
    background: rgba(0,0,0,0.5); justify-content:center; align-items:center;">
    <div style="background:white; padding:20px; border-radius:5px; width:300px; position:relative;">
        <span id="closeGastModal" style="position:absolute; top:10px; right:15px; cursor:pointer;">&times;</span>
        <h3>Neuen User erstellen</h3>
        <form method="POST" action="./create_gast.php">
            <label>Nachname: <input type="text" name="nachname" required></label><br>
            <label>Vorname: <input type="text" name="vorname" required></label><br>

            <label>Strasse: <input type="text" name="strasse" required></label><br>
            <label>Hausnummer: <input type="text" name="hausnr" required></label><br>
            <label>PLZ: <input type="text" name="plz" required></label><br>
            <label>Ort: <input type="text" name="ort" required></label><br>

            <label>Email: <input type="email" name="emal" required></label><br>
            <label>Telefon: <input type="text" name="phone" required></label><br>
            <label>Geburtsdatum: <input type="date" name="geburtsdatum" required></label><br>

            <button type="submit">Gast erstellen</button>
        </form>
    </div>
</div>
