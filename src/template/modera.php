<div>
    <?php
    require_once './db/database.php';

    $dbH = new DatabaseHelper("localhost", "root", "", "edubay", 3306);

    $users = $dbH->getUsers();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if(isset($_POST['utente_blocca'])) {
            $selectedUserIDBlocca = $_POST['utente_blocca'];
            foreach ($selectedUserIDBlocca as $selectedUserID) {
                $dbH->blockUser($selectedUserID);
            }
        }
    
        if(isset($_POST['utente_sblocca'])) {
            $selectedUserIDSblocca = $_POST['utente_sblocca'];
            foreach ($selectedUserIDSblocca as $selectedUserID) {
                $dbH->unlockUser($selectedUserID);
            }
        }
    
        header("Location: {$_SERVER['PHP_SELF']}");
    }

    ?>

    <?php if($dbH->isUserToBlock(0)): 
        $userToBlock = $dbH->userToBlock(0);
    ?>

        <h2>Deve bloccare qualcuno?</h2>
        <form action="#" method="post">
            <?php 
                for ($i = 0; $i < $dbH->isUserToBlock(0); $i++):
                    $user = $userToBlock[$i];
                    ?>

                    <input type="checkbox" id="utente_<?php echo $user['ID']; ?>" name="utente_blocca[]" value="<?php echo $user['ID']; ?>">
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

        <?php if($dbH->isUserToBlock(1)): 
            $userToBlock = $dbH->userToBlock(1);
        ?>

        <h2>Deve sbloccare qualcuno?</h2>
        <form action="#" method="post">
            <?php 
                for ($i = 0; $i < $dbH->isUserToBlock(1); $i++):
                    $user = $userToBlock[$i];
                    ?>

                    <input type="checkbox" id="utente_<?php echo $user['ID']; ?>" name="utente_sblocca[]" value="<?php echo $user['ID']; ?>">
                    <label for="idUtente[]">ID utente:</label><br>
                    <input type="number" id="idUtente[]" name="idUtente[]" readonly value="<?php echo $user['ID']; ?>"><br>    
                    <label for="nomeUtente[]">Username utente:</label><br>
                    <input type="text" id="nomeUtente[]" name="nomeUtente[]" readonly value="<?php echo $user['Username']; ?>"><br>
                    <label for="emailUtente[]">Email utente:</label><br>
                    <input type="text" id="emailUtente[]" name="emailUtente[]" readonly value="<?php echo $user['Email']; ?>"><br><br>
                                    
                <hr>
            <?php endfor; ?>
        
            <input type="submit" value="Sblocca" onclick="bloccaUtente()"><br><br>
        </form>
        <?php endif; ?>

    <?php elseif($dbH->isUserToBlock(1)): 
        $userToUnlock = $dbH->userToBlock(1);
    ?>

        <h2>Deve sbloccare qualcuno?</h2>
        <form action="#" method="post">
            <?php 
                for ($i = 0; $i < $dbH->isUserToBlock(1); $i++): ?>
                    <?php $user = $userToUnlock[$i];?>

                    <input type="checkbox" id="utente_<?php echo $user['ID']; ?>" name="utente_sblocca[]" value="<?php echo $user['ID']; ?>">
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
            var checkboxes = document.querySelectorAll('input[name="utente_blocca"]');            
        }
        function sbloccaUtente() {
            // Ottenere tutti gli elementi checkbox con il nome "checkboxGroup"
            var checkboxes = document.querySelectorAll('input[name="utente_sblocca"]');            
        }
    </script>
</div>
