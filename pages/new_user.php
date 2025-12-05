<!-- Button zum Öffnen des Modals -->
<button id="openModalBtn" class="btn-new-user">➕ Neuen Benutzer erstellen</button>

<!-- Modal Hintergrund -->
<div id="userModal" class="modal-overlay">
    <div class="modal-content">

        <span id="closeModal" class="modal-close">&times;</span>

        <h3>Neuen Benutzer erstellen</h3>

        <form method="POST" action="./create_user.php" class="user-form">

            <label>Username</label>
            <input type="text" name="username" required>

            <label>Name</label>
            <input type="text" name="name" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Passwort</label>
            <input type="password" name="password" required>

            <label>Rolle</label>
            <select name="role" required>
                <option value="1">Admin</option>
                <option value="2">Sachbearbeiter</option>
                <option value="3">Geschäftsführer</option>
            </select>

            <button type="submit" class="btn-save">Benutzer anlegen</button>
        </form>
    </div>
</div>

<style>
/* ─── Button ────────────────────────────────────────────────────── */
.btn-new-user {
    background: #1e90ff;
    color: white;
    padding: 10px 16px;
    border: none;
    border-radius: 6px;
    font-size: 15px;
    cursor: pointer;
    transition: 0.2s;
}
.btn-new-user:hover {
    background: #0f67c5;
}

/* ─── Modal Hintergrund ─────────────────────────────────────────── */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.55);
    justify-content: center;
    align-items: center;
    z-index: 999;
}

/* ─── Modal Box ─────────────────────────────────────────────────── */
.modal-content {
    background: white;
    padding: 25px;
    border-radius: 12px;
    width: 350px;
    animation: modalFade 0.25s ease-out;
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    position: relative;
}
@keyframes modalFade {
    from { opacity: 0; transform: translateY(-15px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ─── Close Icon ────────────────────────────────────────────────── */
.modal-close {
    position: absolute;
    right: 12px;
    top: 10px;
    font-size: 22px;
    cursor: pointer;
    color: #666;
}
.modal-close:hover {
    color: #000;
}

/* ─── Form ──────────────────────────────────────────────────────── */
.user-form {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.user-form input,
.user-form select {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
}

/* ─── Speichern Button ──────────────────────────────────────────── */
.btn-save {
    margin-top: 12px;
    background: #2ecc71;
    color: white;
    padding: 10px 14px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 15px;
    transition: 0.2s;
}

.btn-save:hover {
    background: #25a75a;
}
</style>

<script>
/* Modal öffnen */
document.getElementById('openModalBtn').onclick = () => {
    document.getElementById('userModal').style.display = 'flex';
};

/* Modal schließen */
document.getElementById('closeModal').onclick = () => {
    document.getElementById('userModal').style.display = 'none';
};

/* Klick außerhalb schließt Modal */
window.onclick = (e) => {
    const modal = document.getElementById('userModal');
    if (e.target === modal) modal.style.display = 'none';
};
</script>
