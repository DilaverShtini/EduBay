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

                /** creo prima l'ordine con addOrder(idUtenteInSessione) */
                $dbH->addOrder($_SESSION['ID']);

                /** ottengo il codice dell'ordine appena aggiunto e gli oggetti dell'inserzione */
                $orderCode = $dbH->getOrderCode($_SESSION['ID']);
                $insertionItem = $dbH->getInsertionItem($selectedInsertionIDs);

                /** controllo se posso permettermi l'inserzione da acquistare */
                if($dbH->isMoneyEnough($insertionItem[0]['TotCosto'], $_SESSION['ID'])) {

                    /** creo poi il dettaglio ordine con le inserzioni selezionate con la checkbox ($selectedInsertionsIDs) */
                    foreach ($insertionItem as $object) {
                        /*if ($dbH->isObjectRank($object['Nome'])) {
                            $dbH->updateObjectRank($object['Nome']);
                        } else {
                            $dbH->addObjectRank($object['Nome']);
                        }*/

                        $dbH->addOrderDetail($orderCode[0]['Cod_Ordine'], $selectedInsertionIDs);
                    }
                    $dbH->removeMoney($insertionItem[0]['TotCosto'], $_SESSION['ID']);
                    $dbH->disactiveInsertion($selectedInsertionIDs);
                } else {
                    echo "Ordine non effettuato...controlla il portafoglio";
                }
            }
        }
        header("Location: {$_SERVER['PHP_SELF']}");
    }

    ?>

    <?php 
        $addressAct = $dbH->isAddressSelected($_SESSION['ID']);
        if($dbH->isInsertion() && $addressAct['nAddress']): ?>

        <h2>Scegli l'inserzione da acquistare!</h2>
        <form action="#" method="post">
            <?php for ($i = 0; $i < $insertionsCount[0]['nInsertion']; $i++): ?>
                <?php  if($insertions[$i]['Attivo']): ?>
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
                <?php endif; ?>
            <?php endfor; ?>
        
            <input type="submit" value="Acquista!"><br><br>
        </form>
    <?php else: ?>
        <div>
            Nessuna inserzione da acquistare..
        </div>
    <?php endif; ?>

</div>
