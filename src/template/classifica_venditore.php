<div>
    <?php
    require_once './db/database.php';

    $dbH = new DatabaseHelper("localhost", "root", "", "edubay", 3306);

    $sellers = $dbH->getSellerClassification();
    $sellersCount = $dbH->getSellerCount();

    ?>

    <?php if($sellersCount[0]['nSeller']): ?>

        <h2>Ecco la classifica degli venditori più affidabili!</h2>
        <form action="#" method="post">

            <label><h5>Classifica:</h5></label><br>
            <?php for ($i = 0; $i < $sellersCount[0]['nSeller']; $i++): ?>
                <?php /*$seller = $sellers[$i];
                    $sellerInfo = $dbH->getSeller($seller['IDRecensito']);
                */?>
                <?php $seller = $sellers[$i]; ?>

                    <label for="posizioneClassifica"><?php echo $i+1; ?>.</label>
                    <input type="text" id="nomeVenditore[]" name="nomeVenditore[]" readonly value="<?php echo $seller['NomeUtente']; ?>">
                    <input class="valutazione" type="text" id="valutazioneOggetto[]" name="valutazioneOggetto[]" readonly value="<?php echo $seller['AvgStella']; ?>"><br><br>

            <?php endfor; ?>
            <hr>
        </form>
    <?php else: ?>
        <div>
            Nessun venditore presente in classifica..
        </div>
    <?php endif; ?>

</div>