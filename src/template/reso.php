<div>
    <?php
    require_once './db/database.php';
    $dbH = new DatabaseHelper("localhost", "root", "", "edubay", 3306);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST['inserzione'])) {
            $descrizioneReso = $_POST["descrizioneReso"];
            $selectedInsertionIDs = $_POST['inserzione'];
            foreach ($selectedInsertionIDs as $selectedInsertionID) {
                $dbH->addReso($descrizioneReso);
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

            <?php $detailInsertion = $dbH->getInsertionDetailFromIDAndUser($insertion['ID_Inserzione'], $_SESSION['ID']); ?>

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

    <textarea rows="5" cols="30" name="descrizioneReso" placeholder="Motiva il tuo reso" required></textarea><br>

    
       <input type="submit" value="Rendi!"><br><br>
    </form>

</div>
