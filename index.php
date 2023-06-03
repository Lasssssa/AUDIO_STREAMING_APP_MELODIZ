<?php
    session_start();   
    if(!isset($_SESSION['identified']) || !$_SESSION['identified']){
        header("Location: login.php");
    }
?>

<!-- 
    - Footbar :
        - Lecture
        - Son 
        - Image music 
        - Titre music
        - Artiste
        - Next
        - Previous 
        - Like 
        
    - Playlist :

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
        <input type="hidden" id="id_perso" value="<?php echo $_SESSION['id'] ?>">
        <div>
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
        </div>

        <div id="errors">

        </div>

        <div id="container">


                
        </div>

        <div class="footbar bg-danger">
            <div class="row">
                <div class="col-1 d-flex align-items-center justify-content-center">
                    <img src="playlist/default.png" id="imgMusic" alt="lecture" class="img-fluid littleMargin" style="max-width:55px;max-height:55px">
                </div>
                <div class="col-1 align-items-center justify-content-center">
                    <div class="littleMargin titleMusic">
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
                    <input type="range" class="progressBar" style="width: 500px; accent-color: #000000;">
                    <label for="volume">1:00</label>
                </div>

                <div class="col-1 d-flex align-items-center justify-content-center">
                    <audio class="music" id="musicPlaying" src="musique/gambino.mp3"></audio>
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

