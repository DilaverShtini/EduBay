<div>
    <?php
    require_once './db/database.php';

    $dbH = new DatabaseHelper("localhost", "root", "", "edubay", 3306);

    $items = $dbH->getItemClassification();
    $itemsCount = $dbH->getItemCount();

    ?>

    <?php if($itemsCount[0]['nItem']): ?>

        <h2>Ecco la classifica degli oggetti più venduti!</h2>
        <form action="#" method="post">

            <label><h5>Classifica:</h5></label><br>
            <?php for ($i = 0; $i < $itemsCount[0]['nItem']; $i++): ?>
                <?php $item = $items[$i];?>

                    <label for="posizioneClassifica"><?php echo $i+1; ?>.</label>
                    <input type="text" id="nomeOggetto[]" name="nomeOggetto[]" readonly value="<?php echo $item['NomeOggetto']; ?>">
                    <input class="quantitaOggetto" type="text" id="nomeOggetto[]" name="nomeOggetto[]" readonly value="<?php echo $item['Qta']; ?>"><br><br>

            <?php endfor; ?>
            <hr>
        </form>
    <?php else: ?>
        <div>
            Nessun oggetto presente in classifica..
        </div>
    <?php endif; ?>

</div>