<div>
    <?php
    require_once './db/database.php';

    $dbH = new DatabaseHelper("localhost", "root", "", "edubay", 3306);

    $insertions = $dbH->getInsertions();
    $insertionsCount = $dbH->getNumberOfInsertions();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

    }

    ?>

    <h2>Scegli l'inserzione da acquistare!</h2>

    <?php for ($i = 0; $i < $insertionsCount[0]['nInsertion']; $i++): ?>
        <?php $insertion = $insertions[$i];?>
        
        <input type="checkbox" id="inserzione" name="inserzione" value="Inserzione">
        <label><h6>Descrizione Inserzione: <?php echo $insertion['Descrizione']; ?></h6></label>

        <?php foreach ($dbH->getInsertionObjects($insertion['UniqueID']) as $object): ?>
            <form action="#" method="post">
                <label for="nomeOggetto[]">Nome oggetto:</label><br>
                <input type="text" id="nomeOggetto[]" name="nomeOggetto[]" readonly value="<?php echo $object['Nome']; ?>"><br>
                <label for="prezzoOggetto[]">Prezzo:</label><br>
                <input type="number" id="prezzoOggetto[]" name="prezzoOggetto[]" readonly value="<?php echo $object['Prezzo_unitario']; ?>"><br>
                <label for="usuraOggetto[]">Livello usura (0 come nuovo | 5 molto danneggiato):</label><br>
                <input type="number" id="usuraOggetto[]" name="usuraOggetto[]" readonly value="<?php echo $object['Usura']; ?>"><br><br>
            </form>
        <?php endforeach; ?>
 
        <p>Prezzo inserzione: <?php echo $insertion['TotCosto']; ?></p>
        <hr>
    <?php endfor; ?>
    
    <input type="submit" value="Acquista!" onclick="verificaCheckbox()"><br><br>

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
