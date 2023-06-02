<?php
    session_start();
    
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
                    <a class="navbar-brand" href="index.php">
                        <span class="material-symbols-outlined">
                            home
                        </span>
                    </a>
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


    </body>
    <script type="module" src="js/accueil.js"></script>
</html>