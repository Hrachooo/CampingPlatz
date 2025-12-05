<html>
<head>
    <style>
        #topbar{
            width: auto;
            height: 4rem;
            background-color: #2c3e50;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem;          
        }

        #title{
            color: white;
        }

        #btn {
          background-color: #3498db;       
          color: #fff;                     
          border: none;                   
          padding: 12px 24px;             
          font-size: 16px;                
          border-radius: 6px;            
          cursor: pointer;                 
          transition: all 0.3s ease;       
        }

        #btn:hover {
            background-color: #2980b9;     
            transform: translateY(-3px);    
            box-shadow: 0 6px 12px rgba(0,0,0,0.2);
        }

        #formular {
            margin: 2rem;
            padding: 1.5rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            min-width: 500px;
            background-color: #f9f9f9;
            max-width: 500px;
        }

        label {
            display: block;
            margin-top: 1rem;
            font-weight: bold;
        }

        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 0.5rem;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        #submit {
            margin-top: 1.5rem;
            background-color: #27ae60;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }

        #submit:hover {
            background-color: #1e8449;
        }
    </style>
</head>

<body style="padding: 0; margin: 0;overflow-x: hidden;">
    <div id="topbar">
        <h2 id="title">Campingplatz</h2>
        <button id="btn" onclick="window.location.href='login.php'">Login</button>
    </div>

    <div style="display: flex; justify-content: center">
    
        <div id="formular">
            <h3>Buchungsformular</h3>
            <form action="./actions/plaetze_anzeigen.php" method="post">
                <label for="anreise_datum">Anreise Datum:</label>
                <input type="date" id="anreise_datum" name="anreise_datum" required>

                <label for="abreise_datum">Abreise Datum:</label>
                <input type="date" id="abreise_datum" name="abreise_datum" required>

                <label for="erwachsene">Anzahl Erwachsene:</label>
                <input type="number" id="erwachsene" name="erwachsene" min="0" required>

                <label for="kinder">Anzahl Kinder:</label>
                <input type="number" id="kinder" name="kinder" min="0" required>
                
                <label for="qm">Auswahl Stellplatz:</label>
                <select id="qm" name="qm">
                    <option value="30">Kleiner Platz 30qm</option>
                    <option value="50">Mittelgrosser Platz 50qm</option>
                    <option value="65">Grosser 65qm</option>
                </select>

                <label for="tier">Haustier:</label>
                <select id="tier" name="tier">
                    <option value="false">Nein</option>
                    <option value="true">Ja</option>
                </select>

                <label for="wasser">Wasseranschluss:</label>
                <input type="checkbox" id="wasser" name="wasser" value="1">

                <label for="strom">Stromanschluss:</label>
                <input type="checkbox" id="strom" name="strom" value="1">

                <input type="submit" id="submit" value="Freie Stellplaetze anzeigen">
            </form>
        </div>  
    </div>
</body>
</html>


