<?php
    session_start();   
    if(!isset($_SESSION['identified']) || !$_SESSION['identified']){
        header("Location: login.php");
    }
    $_SESSION['erreurIdentification'] = false;
    $_SESSION['erreurCreation'] = false;

    //PHP POUR UPLOAD DES PHOTOS + 2MO

    //CHMOD 755 SUR PHOTO_PROFIL
    //VERIFIER LE .HTACCESS
    //a2enmod rewrite
    //sudo service apache2 restart
    //AllowOverride All dans le fichier /etc/apache2/ .conf
    if(isset($_POST['submit_photo'])){
        require_once("database.php");
        $chemin = 'photo_profil/'.$_SESSION['nom'].'_'.$_SESSION['prenom'].'.png';
        move_uploaded_file($_FILES['photo_profil']['tmp_name'], $chemin);
        $db = dbConnect();
        changePath($db, $_SESSION['id'], $chemin);
    }
?>

<!-- 
    - Footbar :
        - Next
        - Previous 
        - Like 
 -->

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

    <!-- Button trigger modal -->

        <!-- Modal -->
        <div class="modal fade" id="modalPlaylist" tabindex="1" aria-labelledby="modalPlaylist" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">AJOUTER UNE PLAYLIST À VOTRE COMPTE</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Input de type text : -->
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Nom de la playlist :</label>
                        <input type="text" class="form-control" id="namePlaylist">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">FERMER</button>
                    <button type="button" id="addPlaylistButton" class="btn btn-danger colorRed">AJOUTER</button>
                </div>
                </div>
            </div>
        </div>

        <input type="hidden" id="id_perso" value="<?php echo $_SESSION['id'] ?>">
        
        <nav class="navbar navbar-expand-lg navbar-light bg-danger fixed-top">
            <div class="container">
                <button class="btn clear" id="reset">
                    <span class="material-symbols-outlined">
                        home
                    </span>
                </button>

                <div class="col-6 d-flex justify-content-center">
                    <input type="text" class="form-control" id="rechercheText">
                    <button class="btn btn-secondary" id="recherche">
                        <span class="material-symbols-outlined">search</span>
                    </button>
                </div>

                <div class="col-3 d-flex justify-content-end">
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle text-center" id="nameAccount" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo $_SESSION['prenom'][0] .'.'. $_SESSION['nom'] ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" id="persoAccount" >Compte</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="login.php">Déconnexion</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>


        <!-- <div>
            <nav class="navbar bg-danger">
                <div class="container">
                    <button class="btn clear" id="reset">
                        <span class="material-symbols-outlined">
                            home
                        </span>
                    </button>
                    <div class="row">
                        <div class="col-12"><h4 class="navbar-brand text-center">Accueil</h4></div>
                    </div>
                    <p> </p>
                </div>
            </nav>
        </div> -->

        <div id="errors">

        </div>

        <div id="container">
        
        


        </div>

        <div class="footbar bg-danger">
            <div class="row">
                <div class="col-1 d-flex align-items-center justify-content-center">
                    <img src="playlist/default.png" id="imgMusic" alt="lecture" class="img-fluid littleMargin" style="max-width:55px;max-height:55px">
                </div>
                <div class="col-1 position-relative">
                    <div class="position-absolute top-50 start-50 translate-middle titleMusic">
                        <h3 id="titleMusic">Aucun Titre</h3>
                        <h5 id="artistMusic">.</h5>
                    </div>
                </div>
                <div class="col-1 position-relative">
                    <button type="button" class="position-absolute top-50 start-50 translate-middle clear" id="buttonLiked">
                        <span class="material-symbols-outlined">
                            favorite
                        </span>
                    </button>
                </div>
                    <div class="col-6 text-center">
                    <button type="button" class="btn clear" id="playMusic"><i class="material-icons">play_arrow</i></button><br>
                    <label for="volume">0:00</label>
                    <input type="range" class="progressBar" min="0" max="100" value="0" style="width: 500px; accent-color: #000000;">
                    <label for="volume">1:00</label>
                </div>

                <div class="col-1 d-flex align-items-center justify-content-center">
                    <audio class="music" id="musicPlaying" src="xxx.mp3"></audio>
                    <button type="button" class="btn clear" id="mute"><i class="material-icons">volume_up</i></button>
                </div>
                <div class="col-2 d-flex align-items-center justify-content-center">
                    <input type="range" name="" id="changeVolume" style="width:200px; accent-color: #000000;">
                </div>

            </div>
        </div>

        
    </body>

    
    <script type="module" src="js/accueil.js"></script>

    
</html>

