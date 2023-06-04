<?php 
    session_start (); 
    if(!isset($_SESSION['identified'])|| $_SESSION['identified'] == false){
        header('Location: login.php');
    }
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title> index </title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
        <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300&display=swap" rel="stylesheet">
        <link href="style.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    
    </head>

    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-danger fixed-top">
            <div class="container">
                <a href ="index.php" class="navbar-brand">
                    <button class="btn clear">
                        <span class="material-symbols-outlined">
                            home
                        </span>
                    </button>
                </a>

                <div class="col-6 d-flex justify-content-center">
                    <input type="text" class="form-control">
                    <button class="btn btn-secondary">
                        <span class="material-symbols-outlined">search</span>
                    </button>
                </div>

                <div class="col-3 d-flex justify-content-end">
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle text-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo $_SESSION['prenom'][0] .'.'. $_SESSION['nom'] ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item"  href="perso.php">Compte</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="login.php">Déconnexion</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <?php
            require_once('database.php');
            $db = dbConnect();
            if(isset($_POST['submit_photo'])){
                move_uploaded_file($_FILES['photo_profil']['tmp_name'],"photo_profil/".$_SESSION['nom'].'_'.$_SESSION['prenom'].".png");
            }
        ?>
        <input type="hidden" id="id_user" name="id" value="<?php echo $_SESSION['id']; ?>">

        <div id="errors">

        </div>
        
        <div id="container">

        </div>

        <script type="module" src="js/perso.js"></script>

    </body>
</html>

    