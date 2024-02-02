<div>
    <?php
        require_once './db/database.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $descrizioneOggetto = $_POST['descrizioneOggetto'];
            $idUtente = $_SESSION['ID'];
            $dbH = new DatabaseHelper("localhost", "root", "", "edubay", 3306);
        
            $dbH->addInsertion($descrizioneOggetto, $idUtente);
            $lastInsertionId = $dbH->getLastInsertionId();

            $categorieOggetto = $_POST['categoriaOggetto'];
            $nomiOggetto = $_POST['nomeOggetto'];
            $prezziOggetto = $_POST['prezzoOggetto'];
            $usureOggetto = $_POST['usuraOggetto'];

            for ($i = 0; $i < count($nomiOggetto); $i++) {
                $categoriaOggetto = $categorieOggetto[$i];
                $nomeOggetto = $nomiOggetto[$i];
                $prezzoOggetto = $prezziOggetto[$i];
                $usuraOggetto = $usureOggetto[$i];

                $dbH->addObject($categoriaOggetto, $nomeOggetto, $prezzoOggetto, $usuraOggetto, $lastInsertionId[0]['ID']);
            }

            $dbH->addTotalInInsertion($lastInsertionId[0]['ID']);
        }
    ?>
    <script>
        var indiceCampo = 1;

        function aggiungiCampo() {
            var container = document.getElementById("campiAggiuntivi");
            
            var div = document.createElement("div");

            var labelCategoria = document.createElement("label");
            labelCategoria.textContent = "Categoria oggetto:";
            var inputCategoria = document.createElement("input");
            inputCategoria.type = "text";
            inputCategoria.name = "categoriaOggetto[]";

            var labelNome = document.createElement("label");
            labelNome.textContent = "Nome oggetto:";
            var inputNome = document.createElement("input");
            inputNome.type = "text";
            inputNome.name = "nomeOggetto[]";

            var labelPrezzo = document.createElement("label");
            labelPrezzo.textContent = "Prezzo:";
            var inputPrezzo = document.createElement("input");
            inputPrezzo.type = "number";
            inputPrezzo.step="0.01";
            inputPrezzo.name = "prezzoOggetto[]";

            var labelUsura = document.createElement("label");
            labelUsura.textContent = "Livello usura (0 come nuovo | 5 molto danneggiato):";
            var inputUsura = document.createElement("input");
            inputUsura.type = "number";
            inputUsura.name = "usuraOggetto[]";
            inputUsura.value = 0;

            div.appendChild(document.createElement("br"));
            div.appendChild(labelCategoria);
            div.appendChild(document.createElement("br"));
            div.appendChild(inputCategoria);
            div.appendChild(document.createElement("br"));

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
        <label for="nomeOggetto">Categoria oggetto:</label><br>
        <input type="text" id="categoriaOggetto[]" name="categoriaOggetto[]" required placeholder="Categoria oggetto"><br>
        <label for="nomeOggetto">Nome oggetto:</label><br>
        <input type="text" id="nomeOggetto[]" name="nomeOggetto[]" required placeholder="Nome oggetto"><br>
        <label for="prezzoOggetto">Prezzo:</label><br>
        <input type="number" id="prezzoOggetto[]" step="0.01" name="prezzoOggetto[]" required><br>
        <label for="usuraOggetto">Livello usura (0 come nuovo | 5 molto danneggiato):</label><br>
        <input type="number" min="0" max="5" id="usuraOggetto[]" name="usuraOggetto[]" value=0><br>

        <div id="campiAggiuntivi"></div><br>
        
        <input type="button" value="+" onclick="aggiungiCampo()"><br><br>

        <input type="submit" value="Crea inserzione!">
    </form> 

<div>
