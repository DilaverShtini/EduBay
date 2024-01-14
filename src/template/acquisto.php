<div>
    <?php
    require_once './db/database.php';

    $dbH = new DatabaseHelper("localhost", "root", "", "edubay", 3306);

    $insertions = $dbH->getInsertions();
    $insertionsCount = $dbH->getNumberOfInsertions();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST['inserzione'])) {
            $selectedInsertionID = $_POST['inserzione'];
            foreach ($selectedInsertionID as $selectedInsertionIDs) {
                echo "Hai selezionato l'Inserzione con UniqueID: $selectedInsertionIDs";
                /** creo prima l'ordine con addOrder(idUtenteInSessione) */
                
                /** creo poi il dettaglio ordine con le inserzioni selezionate con la checkbox ($selectedInsertionsIDs)
                 *  (come incrementare numero linea? non puo essere A.I. visto che ho due chiavi primiarie) */
                
                 /** inserisco gli oggetti dell'inserzione nel dettaglio ordine (ogni riga in dettaglio ordine corrisponde ad un oggetto
                  *  bhper facilitare il reso di un solo oggetto) */
            }

        }
    }

    ?>

    <h2>Scegli l'inserzione da acquistare!</h2>

    <form action="#" method="post">
        <?php for ($i = 0; $i < $insertionsCount[0]['nInsertion']; $i++): ?>
            <?php $insertion = $insertions[$i];?>
            
            <input type="checkbox" id="inserzione_<?php echo $insertion['UniqueID']; ?>" name="inserzione[]" value="<?php echo $insertion['UniqueID']; ?>">
            <label><h6>Descrizione Inserzione: <?php echo $insertion['Descrizione']; ?></h6></label>

            <?php foreach ($dbH->getInsertionObjects($insertion['UniqueID']) as $object): ?>
                <label for="nomeOggetto[]">Nome oggetto:</label><br>
                <input type="text" id="nomeOggetto[]" name="nomeOggetto[]" readonly value="<?php echo $object['Nome']; ?>"><br>
                <label for="prezzoOggetto[]">Prezzo:</label><br>
                <input type="number" id="prezzoOggetto[]" name="prezzoOggetto[]" readonly value="<?php echo $object['Prezzo_unitario']; ?>"><br>
                <label for="usuraOggetto[]">Livello usura (0 come nuovo | 5 molto danneggiato):</label><br>
                <input type="number" id="usuraOggetto[]" name="usuraOggetto[]" readonly value="<?php echo $object['Usura']; ?>"><br><br>
            <?php endforeach; ?>
    
            <p>Prezzo inserzione: <?php echo $insertion['TotCosto']; ?></p>
            <hr>
        <?php endfor; ?>
    
       <input type="submit" value="Acquista!" onclick="verificaCheckbox()"><br><br>
    </form>

    <script>
        function verificaCheckbox() {
            // Ottenere tutti gli elementi checkbox con il nome "checkboxGroup"
            var checkboxes = document.querySelectorAll('input[name="inserzione"]');

            // Iterare attraverso gli elementi e verificare se sono checkati
            checkboxes.forEach(function(checkbox) {
                console.log(checkbox.value + ': ' + checkbox.checked);
            });
        }
    </script>
</div>
