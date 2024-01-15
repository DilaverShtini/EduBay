<div>
    <?php
        require_once './db/database.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $numCivico = $_POST['numCivico'];
            $idUtente = $_SESSION['ID'];
            $via = $_POST['via'];
            $cap = $_POST['cap'];
            $nAddressOfUser = $dbh -> countAddressOfUser($idUtente);
            
            $dbH = new DatabaseHelper("localhost", "root", "", "edubay", 3306);

            if($nAddressOfUser === 0){
                $attivo = 1;
            }else{
                $attivo = 0;
            }
            
            $dbH->addAddress($numCivico, $via, $cap, $attivo, $idUtente);
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
        var_dump($addressOfUser);

        foreach ($addressOfUser as $address):
        ?>
            <?php if (isset($address["Via"])) : ?>
                <p> <?php echo $address["Via"] ?></p>
            <?php endif; ?>
        <?php
        endforeach;
        ?>
</div><br>

<div>
