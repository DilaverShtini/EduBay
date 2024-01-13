<!DOCTYPE html>
<?php require_once './db/database.php';
        $dbh = new DatabaseHelper("127.0.0.1", "root", "", "EduBay", 3307); ?>
<html lang="it">
    <head>
        <title><?php echo $templateParams["titolo"]; ?></title>
        <meta charset="UTF-8"/>
        <link rel="stylesheet" href="style/style.css" aria-atomic="">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Major Mono Display">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    </head>

    <body class="bg-light">
        <header class="bg-black fixed-top d-flex justify-content-between align-items-center">
            <!--<h1 class="text-light"><button><a href="home.php" style="text-decoration:none"> <img src="images\logo.png" width=70>SoundDrift </a></button></h1>
            -->

            <a href="index.php">
                <!-- da rivedere per il discorso del percorso assoluto -->
                    <img src="#" width="70" style="display:inline-block">
                    <h1 class="text-white" style="display:inline-block; font-size:27px" >EduBay</h1>
            </a>

            <div>

                <?php
                if(isset($_SESSION["ID"])) {
                    
                ?>
                    Saldo Corrente: <?php $wallet = $dbh->getWalletOfUser($_SESSION["ID"]);
                    echo $wallet["Saldo"];?> €
                    <a href="logout.php" class="btn btn-dark" style="text-decoration:none">
                        <i class="bi bi-box-arrow-left" style="font-size: 20px"></i>
                    </a>
                <?php
                } else {
                ?>
                    <a href="login.php" class="btn btn-dark" style="text-decoration:none">
                        <i class="bi bi-box-arrow-in-right" style="font-size: 20px"></i>
                    </a>
                <?php
                }
                ?>
            </div>
        </header>
        
        <main>
            <?php 
                if(isset($templateParams["nome"])){
                    require($templateParams["nome"]);
                }
            ?>
        </main>
        
        <footer id="second-footer" class="bg-black text-center">
            <div class="container">
                <a href="acquisto.php" class="btn btn-dark" style="text-decoration:none">
                        Acquista
                </a>
                <a href="crea.php" class="btn btn-dark" style="text-decoration:none">
                        Crea
                </a>
                <a href="reso.php" class="btn btn-dark" style="text-decoration:none">
                        Reso
                </a>
            </div>
        </footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
</html>