<div>
    <?php
        require_once './db/database.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $descrizioneOggetto = $_POST['descrizioneOggetto'];
            $idUtente = $_SESSION['ID'];
            $dbH = new DatabaseHelper("localhost", "root", "", "edubay", 3306);
        
            $dbH->addInsertion($descrizioneOggetto, $idUtente);
            $lastInsertionId = $dbH->getLastInsertionId();

            $nomiOggetto = $_POST['nomeOggetto'];
            $prezziOggetto = $_POST['prezzoOggetto'];
            $usureOggetto = $_POST['usuraOggetto'];

            for ($i = 0; $i < count($nomiOggetto); $i++) {
                $nomeOggetto = $nomiOggetto[$i];
                $prezzoOggetto = $prezziOggetto[$i];
                $usuraOggetto = $usureOggetto[$i];

                $dbH->addObject($nomeOggetto, $prezzoOggetto, $usuraOggetto, $lastInsertionId[0]['ID']);
            }

            $dbH->addTotalInInsertion($lastInsertionId[0]['ID']);
        }
    ?>
    <script>
        var indiceCampo = 1;

        function aggiungiCampo() {
            var container = document.getElementById("campiAggiuntivi");
            
            var div = document.createElement("div");

            var labelNome = document.createElement("label");
            labelNome.textContent = "Nome oggetto:";
            var inputNome = document.createElement("input");
            inputNome.type = "text";
            inputNome.name = "nomeOggetto[]";

            var labelPrezzo = document.createElement("label");
            labelPrezzo.textContent = "Prezzo:";
            var inputPrezzo = document.createElement("input");
            inputPrezzo.type = "number";
            inputPrezzo.name = "prezzoOggetto[]";
            inputPrezzo.value = 0;

            var labelUsura = document.createElement("label");
            labelUsura.textContent = "Livello usura (0 come nuovo | 5 molto danneggiato):";
            var inputUsura = document.createElement("input");
            inputUsura.type = "number";
            inputUsura.name = "usuraOggetto[]";
            inputUsura.value = 0;

            div.appendChild(document.createElement("br"));
            div.appendChild(labelNome);
            div.appendChild(document.createElement("br"));
            div.appendChild(inputNome);
            div.appendChild(document.createElement("br"));

            div.appendChild(labelPrezzo);
            div.appendChild(document.createElement("br"));
            div.appendChild(inputPrezzo);
            div.appendChild(document.createElement("br"));

            div.appendChild(labelUsura);
            div.appendChild(document.createElement("br"));
            div.appendChild(inputUsura);
            div.appendChild(document.createElement("br"));

            container.appendChild(div);

            indiceCampo++; // Incrementa l'indice per rendere unici i nomi degli input

        }
    </script>

    <h2>Inserisci i dati nei campi per creare un inserzione!</h2>

    <form action="#" method="post">
        <label for="descrizioneOggetto">Descrizione Inserzione:</label><br>
        <textarea rows="5" cols="30" name="descrizioneOggetto" placeholder="Descrizione inserzione"></textarea><br>
        <label for="nomeOggett">Nome oggetto:</label><br>
        <input type="text" id="nomeOggetto[]" name="nomeOggetto[]" placeholder="Nome oggetto"><br>
        <label for="prezzoOggetto">Prezzo:</label><br>
        <input type="number" id="prezzoOggetto[]" name="prezzoOggetto[]" value=0><br>
        <label for="usuraOggetto">Livello usura (0 come nuovo | 5 molto danneggiato):</label><br>
        <input type="number" id="usuraOggetto[]" name="usuraOggetto[]" value=0><br>

        <div id="campiAggiuntivi"></div><br>
        
        <input type="button" value="+" onclick="aggiungiCampo()"><br><br>

        <input type="submit" value="Crea inserzione!">
    </form> 

<div>
