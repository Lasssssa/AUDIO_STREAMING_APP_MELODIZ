<?php 
    session_start();
    $_SESSION['identified'] = false;
    $_SESSION['alreadyExist'] = false;
    $_SESSION['passwordNotConfirmed'] = false;
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
        <title>Page Connexion</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
        <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300&display=swap" rel="stylesheet">
        <script src="script.js" defer></script>
        <link href="styleLoginPage.css" rel="stylesheet">
    </head>   
    <body>

    <?php
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
            require_once("database.php");
            $dbConnection = dbConnect();
            if(isset($_POST['envoyer']) && isset($_POST['email']) && isset($_POST['password'])){
                $email = $_POST['email'];
                if(isValidUser($email, $dbConnection)){
                    $encryptedPassword = getEncryptedPassword($email, $dbConnection);
                    if(password_verify($_POST['password'], $encryptedPassword)){
                        $user = getUser($email, $dbConnection);
                        $_SESSION['email'] = $user['user_mail'];
                        $_SESSION['nom'] = $user['user_lastname'];
                        $_SESSION['prenom'] = $user['user_firstname'];
                        $_SESSION['id'] = $user['id'];
                        $_SESSION['telephone'] = $user['user_telephone'];
                        $_SESSION['naissance'] = $user['user_birth'];
                        $_SESSION['creation'] = $user['creation_date'];
                        $_SESSION['bio'] = $user['user_bio'];
                        $_SESSION['identified'] = true;
                        header("Location: index.php");
                        }else{
                            $_SESSION['erreurIdentification'] = true;
                        } 
                    }else{
                        $_SESSION['erreurIdentification'] = true;
                    }
                }
            ?>

        <div class="container">
            <div class="rightLogin">
            </div>
            <div class="centerLogin">
                <div class="imgLogin">
                    <img src="img/logo.png" alt="logo" width="150px">
                </div>
                <div class="titleLogin">
                    <h1>RED BY SPOTIFY</h1>
                </div>
                <div class="loginBox shadow">
                    <h1>CONNEXION</h1>
                    <form action="login.php" method="post">
                        <div class="mb-3 row">
                            <label for="inputPassword" class="col-sm-2 col-form-label"><span class="material-symbols-outlined">alternate_email</span></label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" name="email" placeholder="Adresse Mail">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="inputPassword" class="col-sm-2 col-form-label"><span class="material-symbols-outlined">lock</span></label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe">
                            </div>
                        </div>
                        <div class="form-check form-switch" id="ecarted">
                            <input class="form-check-input" id="showPassword" type="checkbox" role="switch" onchange="togglePassword()">
                            <label class="form-check-label" for="flexSwitchCheckChecked">Afficher votre mot de passe</label>
                        </div>
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary colorRed" type="submit" name="envoyer">Me connecter</button>
                        </div>
                    </form>
                    <br>
                    <div class="lien">
                        <a href="register.php">Pas encore inscrit ?</a>
                    </div>
                </div>
            </div>
            <div class="leftLogin">
            </div>
        </div>
        
    </body>
</html>