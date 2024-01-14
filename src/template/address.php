<div>
    <?php
        require_once './db/database.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $numCivico = $_POST['numCivico'];
            $idUtente = $_SESSION['ID'];
            $via = $_POST['via'];
            $cap = $_POST['cap'];
            $nAddressOfUser = $dbh -> countAddressOfUser($idUtente);
            
            $dbH = new DatabaseHelper("localhost", "root", "", "edubay", 3307);

            if($nAddressOfUser === 0){
                $attivo = 1;
            }else{
                $attivo = 0;
            }
            
            $dbH->addAddress($numCivico, $via, $cap, $attivo, $idUtente);

            header("Location: {$_SERVER['PHP_SELF']}");
        }
    ?>

    <h2>Crea un indirizzo!</h2>

    <form method="post">
        <label for="numCivico">Numero Civico:</label><br>
        <input type="number" name="numCivico"><br>
        <label for="via">Via:</label><br>
        <input type="text" id="via" name="via" placeholder="Via"><br>
        <label for="cap">Cap:</label><br>
        <input type="number" id="cap" name="cap"><br><br>

        <input type="submit" value="Crea indirizzo!">
    </form> 

    <div id="campiAggiuntivi">
        <?php 
        $dbH = new DatabaseHelper("localhost", "root", "", "edubay", 3307);
        $idUtente = $_SESSION["ID"];
        $addressOfUser = $dbH->getAddressOfUser($idUtente);

        foreach ($addressOfUser as $address):
        ?>
            <?php if (isset($address["Via"])) : ?>
                <p> Via: <?php echo $address["Via"] ?>
                    <button class="azione-button" data-address-id="<?php echo $address['ID']; ?>">Rendi attivo</button>
                    Attivo: <span id="attivo-<?php echo $address['ID']; ?>"><?php echo $address["Attivo"] ?></span>
                </p>
            <?php endif; ?>
        <?php
        endforeach;
        ?>
</div><br>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var azioneButtons = document.querySelectorAll('.azione-button');

    azioneButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var addressId = this.getAttribute('data-address-id');
            var xhr = new XMLHttpRequest();

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                      // Aggiorna dinamicamente tutti i campi
                    var responseData = JSON.parse(xhr.responseText);

                    var viaElement = document.getElementById('via-' + addressId);
                    if (viaElement) {
                        viaElement.innerText = responseData.Via;
                    }

                    var attivoElement = document.getElementById('attivo-' + addressId);
                    if (attivoElement) {
                        attivoElement.innerText = responseData.Attivo;
                    }

                    // Salva nello stato locale per indicare che la richiesta è stata eseguita
                    localStorage.setItem('addressAction_' + addressId, 'executed');

                    location.reload();
                }
            };

            xhr.open('GET', 'azione.php?address_id=' + addressId, true);
            xhr.send();
        });
    });
});
</script>
