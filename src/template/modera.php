<div>
    <?php
    require_once './db/database.php';

    $dbH = new DatabaseHelper("localhost", "root", "", "edubay", 3306);

    $users = $dbH->getUsers();
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

                    /** creo poi il dettaglio ordine con le inserzioni selezionate con la checkbox ($selectedInsertionsIDs)
                    *   bisogna aggiungere l'oggetto in classifica e poi crare l'ordine in dettaglio per la chiave esterna */
                    foreach ($insertionItem as $object) {
                        if ($dbH->isObjectRank($object['Nome'])) {
                            $dbH->updateObjectRank($object['Nome']);
                        } else {
                            $dbH->addObjectRank($object['Nome']);
                        }
                        
                        $dbH->addOrderDetail($orderCode[0]['Cod_Ordine'], $selectedInsertionIDs, $object['Nome']);
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

    <?php if($dbH->isUserToBlock(0)): 
        $userToBlock = $dbH->userToBlock(0);
        var_dump($userToBlock);
    ?>

        <h2>Deve bloccare qualcuno?</h2>
        <form action="#" method="post">
            <?php 
                for ($i = 0; $i < $dbH->isUserToBlock(0); $i++):
                    $user = $userToBlock[$i];
                    ?>

                    <label for="idUtente[]">ID utente:</label><br>
                    <input type="number" id="idUtente[]" name="idUtente[]" readonly value="<?php echo $user['ID']; ?>"><br>    
                    <label for="nomeUtente[]">Username utente:</label><br>
                    <input type="text" id="nomeUtente[]" name="nomeUtente[]" readonly value="<?php echo $user['Username']; ?>"><br>
                    <label for="emailUtente[]">Email utente:</label><br>
                    <input type="text" id="emailUtente[]" name="emailUtente[]" readonly value="<?php echo $user['Email']; ?>"><br><br>
                                    
                <hr>
            <?php endfor; ?>
        
            <input type="submit" value="Blocca" onclick="bloccaUtente()"><br><br>
        </form>

    <?php elseif($dbH->isUserToBlock(1)): 
        $userToUnlock = $dbH->userToBlock(1);
    ?>

        <h2>Deve sbloccare qualcuno?</h2>
        <form action="#" method="post">
            <?php 
                for ($i = 0; $i < $dbH->isUserToBlock(1); $i++): ?>
                    <?php $user = $userToUnlock[$i];?>

                    <label for="idUtente[]">ID utente:</label><br>
                    <input type="number" id="idUtente[]" name="idUtente[]" readonly value="<?php echo $user['ID']; ?>"><br>    
                    <label for="nomeUtente[]">Username utente:</label><br>
                    <input type="text" id="nomeUtente[]" name="nomeUtente[]" readonly value="<?php echo $user['Username']; ?>"><br>
                    <label for="emailUtente[]">Email utente:</label><br>
                    <input type="text" id="emailUtente[]" name="emailUtente[]" readonly value="<?php echo $user['Email']; ?>"><br><br>
                                    
                <hr>
            <?php endfor; ?>
        
            <input type="submit" value="Sblocca" onclick="sbloccaUtente()"><br><br>
        </form>
    <?php else: ?>
        Nessun utente da moderare..
    <?php endif; ?>

    <script>
        function bloccaUtente() {
            // Ottenere tutti gli elementi checkbox con il nome "checkboxGroup"
            var checkboxes = document.querySelectorAll('input[name="inserzione"]');            
        }
    </script>
</div>
