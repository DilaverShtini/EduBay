<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title><?php echo $templateParams["titolo"]; ?></title>

        <!-- Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto%7CVarela+Round">
        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <!-- Custom Style -->
        <link rel="stylesheet" href="./components/signin-modal/signin-modal.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Javascript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        <script src="./components/signin-modal/signin-modal.js" defer></script>
    </head>
    <body id="body">
        <header id="menuLink" class="bg-dark py-2">

            <!-- Logo -->
            <div id="logo" class="text-center">
                <a href="#"><img src="./img/EduBay_logo.png" alt="EduBay"/></a>
            </div>

            <!-- Divider -->
            <div class="d-flex justify-content-center col-12">
                <hr class="dividerClass text-light w-75" />
            </div>  

            <!-- Menu -->
            <div class="container-fluid">
                <div class="row">
                    <nav>   
                        <ul class="nav nav-pills" role="tablist">

                            <!-- Link -->
                            <div id="link" class="d-flex justify-content-center col-6">
                                <nav>
                                    <ul class="menu">
                                        <a href="acquista.php"><li class="menuButton col">Acquista</li></a>
                                        <a href="crea.php"><li class="menuButton col">Crea</li></a>
                                        <a href="reso.php"><li class="menuButton col">Reso</li></a>
                                    </ul>
                                </nav>
                            </div>
                            
                        </ul>
                    </nav>
                </div>
            </div> 
        </header>
        <div class="container-fluid">
            <div class="row">
                <div class="col-3">
                    <aside class="m-2 px-2 py-3">

                    </aside>
                </div>
                <div class="col-md-6 col-sm-12">
                    <main>

                    </main>
                </div>
                <div class="col-3">
                    <aside class="m-2 px-2 py-3">
                        
                    </aside>
                </div>
            </div>
        </div>

        <?php require_once("./components/signin-modal/signin-modal.php") ?>

        <footer class="bg-dark py-2">
        </footer>
    </body>
</html>
