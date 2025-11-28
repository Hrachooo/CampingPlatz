<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Gast erstellen</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 2rem;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .form-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 2rem;
            width: 400px;
        }

        .form-card h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 1rem;
            font-weight: bold;
            color: #34495e;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 0.3rem;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        button {
            width: 100%;
            background-color: #3498db;
            color: #fff;
            border: none;
            padding: 12px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin-top: 1rem;
        }

        button:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="form-card">
        <h2>Gast erstellen</h2>
        <form method="POST" action="./create_gast.php">
            <label>Nachname:
                <input type="text" name="nachname" required>
            </label>

            <label>Vorname:
                <input type="text" name="vorname" required>
            </label>

            <label>Strasse:
                <input type="text" name="strasse" required>
            </label>

            <label>Hausnummer:
                <input type="text" name="hausnr" required>
            </label>

            <label>PLZ:
                <input type="text" name="plz" required>
            </label>

            <label>Ort:
                <input type="text" name="ort" required>
            </label>

            <label>Email:
                <input type="email" name="email" required>
            </label>

            <label>Telefon:
                <input type="text" name="phone" required>
            </label>

            <label>Geburtsdatum:
                <input type="date" name="geburtsdatum" required>
            </label>

            <button type="submit">Gast erstellen</button>
        </form>
    </div>
</body>
</html>
