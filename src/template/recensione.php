<?php require_once './db/database.php'; 
    $dbH = new DatabaseHelper("localhost", "root", "", "edubay", 3306); 

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["opzioni"])) {
            $IdUtenteRecensito = intval($_POST["opzioni"]);
        }
        if (isset($_POST["voto"])) {
            $voto_selezionato = intval($_POST["voto"]);
        }

        $dbH->addReview($IdUtenteRecensito, $_SESSION["ID"], $voto_selezionato);
        $user = $dbH->getUserById($IdUtenteRecensito);

        if ($dbH->isSellerReviewed($user[0]['Username'])) {
            $dbH->updateSellerStar($user[0]['Username'], $IdUtenteRecensito);
        } else {
            $dbH->addSellerStar($user[0]['Username'], $voto_selezionato);
        }

        echo "recensione eseguita con successo";
    }
?>

<h2>Recensione</h2>


<form method="post">
    Recensore: <input type="text" readonly value="<?php echo $dbH->getUserById($_SESSION["ID"])[0]["Username"]; ?>"><br>
    Recensione: <select name="opzioni">
    <?php 
    $users = $dbH->getUsers();
    foreach ($users as $user): ?>
        <?php if($user["ID"] !== $_SESSION["ID"]): ?>
            <option value="<?php echo $user["ID"]; ?>"><?php echo $user["Username"]; ?></option>
        <?php endif; ?>
    <?php endforeach; ?>
    </select><br>
    Num. Stelle: <select name="voto">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
    </select>

    <input type="submit" value="Recensisci">
</form>
