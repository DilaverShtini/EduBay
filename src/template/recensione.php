<?php require_once './db/database.php'; 
    $dbH = new DatabaseHelper("localhost", "root", "", "edubay", 3306); 

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["inserzione"])) {
        
        if (isset($_POST["voto"])) {
            $voto_selezionato = intval($_POST["voto"]);
        }

        if(isset($_POST['inserzione'])) {
            $selectedInsertionID = intval($_POST['inserzione']);
            $dbH->addReviewInDetailOrder($voto_selezionato, $selectedInsertionID);
        }
        $idUserCreator = $dbH->getUserThatCreateInsertion($selectedInsertionID);
        $dbH->updateSellerStar($idUserCreator["ID"]);

        echo "recensione eseguita con successo";
    }
?>

<h2>Recensione</h2>


<form method="post">
    Recensore: <input type="text" readonly value="<?php echo $dbH->getUserById($_SESSION["ID"])[0]["Username"]; ?>"><br><br>
    Num. Stelle: <select name="voto">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
    </select><br><br>
    <h3>Seleziona gli ordini da recensire</h3>
    <?php foreach ($dbH->getInsertionsOnDetailOrder($_SESSION['ID']) as $insertion) { ?>

    
    <?php if(!$dbh->isInsertionActive($insertion['ID_Inserzione'])
    && !$dbh->isDetailOrderReviewed($insertion['ID_Inserzione'])) : ?>

    <?php $detailInsertion = $dbH->getInsertionDetailFromID($insertion['ID_Inserzione']); ?>

    <input type="radio" id="inserzione_<?php echo $detailInsertion[0]['ID']; ?>" name="inserzione" value="<?php echo $detailInsertion[0]['ID']; ?>">
    
    <?php $objectsInInsertion = $dbH->getInsertionObjects($insertion["ID_Inserzione"]);

        foreach ($objectsInInsertion as $object) { ?>
            &nbsp<label for="NomeOggetto[]">Nome Oggetto:</label><br>
            &nbsp<input type="text" id="NomeOggetto[]" name="NomeOggetto[]" readonly value="<?php echo $object['Nome']; ?>"><br>
        <?php } ?>   

    <hr>
    <?php endif; ?>
    <?php } ?>
    

    <input type="submit" value="Recensisci">
</form>
