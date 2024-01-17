<div>
    <?php
    require_once './db/database.php';
    $dbH = new DatabaseHelper("localhost", "root", "", "edubay", 3307);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST['inserzione'])) {
            $selectedInsertionIDs = $_POST['inserzione'];
            foreach ($selectedInsertionIDs as $selectedInsertionID) {
                $dbH->addReso();
                $last_id = $dbH->getLastInsertId(); 
                
                $dbH->addResoInDettaglioOrdine($selectedInsertionID, $last_id);
                //rimborso
                $dbH->rimborso($dbH->getInsertionDetailFromID($selectedInsertionID)[0]["TotCosto"], $_SESSION["ID"]);

                //eliminazione dell'inserzione e degli oggetti 
                $dbH->activateInsertion($selectedInsertionID);

                
            }
        }
        header("Location: {$_SERVER['PHP_SELF']}");
    }

    ?>

    <h2>Scegli l'inserzione o l'oggetto da rendere!</h2>

    <form action="#" method="post">
    <?php foreach ($dbH->getInsertionsOnDetailOrder($_SESSION['ID']) as $insertion) { ?>

        <?php if(!$dbh->isInsertionActive($insertion['ID_Inserzione'])): ?>

            <?php $detailInsertion = $dbH->getInsertionDetailFromID($insertion['ID_Inserzione']); ?>

            <input type="checkbox" id="inserzione_<?php echo $detailInsertion[0]['ID']; ?>" name="inserzione[]" value="<?php echo $detailInsertion[0]['ID']; ?>">
            <label><h5>Descrizione Inserzione: <br><?php echo $detailInsertion[0]['Descrizione']; ?></h5></label><br>

            <?php $objectsInInsertion = $dbH->getInsertionObjects($insertion["ID_Inserzione"]);

                foreach ($objectsInInsertion as $object) { ?>
                    &nbsp<label for="NomeOggetto[]">Nome Oggetto:</label><br>
                    &nbsp<input type="text" id="NomeOggetto[]" name="NomeOggetto[]" readonly value="<?php echo $object['Nome']; ?>"><br>
                    &nbsp<p>Prezzo Oggetto: <?php echo $object['Prezzo_unitario']; ?> €</p><br>
                <?php } ?>   

            <hr>
        <?php endif; ?>
    <?php } ?>

    
       <input type="submit" value="Rendi!" onclick="verificaCheckbox()"><br><br>
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
