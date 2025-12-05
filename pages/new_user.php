<button id="openModalBtn">Neuen User erstellen</button>

<div id="userModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
    background: rgba(0,0,0,0.5); justify-content:center; align-items:center;">
    <div style="background:white; padding:20px; border-radius:5px; width:300px; position:relative;">
        <span id="closeModal" style="position:absolute; top:10px; right:15px; cursor:pointer;">&times;</span>
        <h3>Neuen User erstellen</h3>
        <form method="POST" action="./create_user.php">
            <label>Username:</label><br>
            <input type="text" name="username" required><br><br>

            <label>Name:</label><br>
            <input type="text" name="name" required><br><br>

            <label>Email:</label><br>
            <input type="email" name="email" required><br><br>

            <label>Passwort:</label><br>
            <input type="password" name="password" required><br><br>

            <label for="role">Rolle:</label>
            <select name="role" id="role" required>
                <option value="1">Admin</option>
                <option value="2">Sachbearbeiter</option>
                <option value="3">Geschaeftsfuehrer</option>
            </select>

            <button type="submit">User erstellen</button>
        </form>
    </div>
</div>
