<div>
    <?php
    require_once './db/database.php';
    $conn = new mysqli("localhost", "root", "", "edubay", 3306);
    $dbH = new DatabaseHelper("localhost", "root", "", "edubay", 3306);

    $orderDetails = $dbH->getOrderDetails();
    $orderDetailsCount = $dbH->getNumberOfOrderDetail();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST['oggetto'])) {
            $selectedObjectIDs = $_POST['oggetto'];
            foreach ($selectedObjectIDs as $selectedObjectID) {
                $dbh->addReso();
                $last_id = $conn->insert_id;
                //update delle righe di dettaglio ordine
                //calcolo del numero linea a partire dall'oggetto selezionato
                //all'interno di una inserzione non possono essere presenti due oggetti con il nome uguale
                $dbH->addResoInDettaglioOrdine($numLinea, $last_id);
                //rimborso

                //eliminazione dell'inserzione e degli oggetti 

                
            }
        }
    }

    ?>

    <h2>Scegli l'inserzione o l'oggetto da rendere!</h2>

    <form action="#" method="post">
    <?php foreach ($dbH->getInsertionsOnDetailOrder($_SESSION['ID']) as $insertion) { ?>

        <?php $detailInsertion = $dbH->getInsertionDetailFromID($insertion['ID_Inserzione']); ?>

        <input type="checkbox" id="inserzione_<?php echo $detailInsertion[0]['ID']; ?>" name="inserzione[]" value="<?php echo $detailInsertion[0]['ID']; ?>">
        <label><h5>Descrizione Inserzione: <br><?php echo $detailInsertion[0]['Descrizione']; ?></h5></label><br>

        <?php $objectsInInsertion = $dbH->getInsertionObjects($insertion["ID_Inserzione"]);

            foreach ($objectsInInsertion as $object) { ?>
                &nbsp<input type="checkbox" id="oggetto_<?php echo $object['ID']; ?>" name="oggetto[]" value="<?php echo $object['ID']; ?>">
                &nbsp<label for="NomeOggetto[]">Nome Oggetto:</label><br>
                &nbsp<input type="text" id="NomeOggetto[]" name="NomeOggetto[]" readonly value="<?php echo $object['Nome']; ?>"><br>
                &nbsp<p>Prezzo Oggetto: <?php echo $object['Prezzo_unitario']; ?> €</p><br>
            <?php } ?>   

        <hr>
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
