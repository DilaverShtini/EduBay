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
                    <label for="idVenditore">ID</label>
                    <input type="text" id="IdVenditore[]" name="IdVenditore[]" readonly value="<?php echo $seller['ID']; ?>" size="1" style="text-align: center;" >
                    <label for="usernameVenditore">Username</label>
                    <input type="text" id="nomeVenditore[]" name="nomeVenditore[]" readonly value="<?php echo $seller['Username']; ?>">
                    <label for="valutazioneVenditore">Valutazione</label>
                    <input class="valutazione" type="text" id="valutazioneVenditore[]" name="valutazioneVenditore[]" readonly value="<?php echo $seller['AvgStella']; ?>" size="1" style="text-align: center" ><br><br>

            <?php endfor; ?>
            <hr>
        </form>
    <?php else: ?>
        <div>
            Nessun venditore presente in classifica..
        </div>
    <?php endif; ?>

</div>