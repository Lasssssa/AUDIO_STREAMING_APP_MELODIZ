<?php 
    session_start();
    $_SESSION['identified'] = false;
    $_SESSION['erreurIdentification'] = false;
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
        <script src="script.js" defer></script>
    </head>   
    <body>

        <?php
            if(isset($_POST['envoyer']) && isset($_POST['prenom']) && isset($_POST['nom']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['passwordConfirmed']) && isset($_POST['telephone']) && isset($_POST['naissance'])){
                $date = date("Y-m-d");
                $age = date_diff(date_create($_POST['naissance']), date_create($date))->format('%y');
                if($_POST['password']!= $_POST['passwordConfirmed']){
                    $_SESSION['passwordNotConfirmed'] = true;
                }
                else if($age <5 || $age > 100 || strlen($_POST['naissance']) != 10){
                    $_SESSION['ageNotValid'] = true;
                }else if(strlen($_POST['telephone']) != 10){
                    $_SESSION['erreurTelephone'] = true;
                }
                else{
                    $_SESSION['erreurTelephone'] = false;
                    $_SESSION['ageNotValid'] = false;
                    $_SESSION['passwordNotConfirmed'] = false;
                    $prenom = $_POST['prenom'];
                    $nom = $_POST['nom'];
                    $email = $_POST['email'];
                    $telephone = $_POST['telephone'];
                    $creation_date = date("Y-m-d");
                    $birthdate = $_POST['naissance'];
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    require_once('database.php');
                    $db = dbConnect();
                    $isAddedUser = addUser($db, $prenom, $nom, $email, $password, $telephone, $creation_date, $birthdate);
                    if($isAddedUser){
                        $date = date("Y-m-d");
                        $user = getUser($email, $db);
                        setUpUser($db, $user['id'], $date);
                        $_SESSION['email'] = $user['user_mail'];
                        $_SESSION['nom'] = $user['user_lastname'];
                        $_SESSION['prenom'] = $user['user_firstname'];
                        $_SESSION['id'] = $user['id'];
                        $_SESSION['telephone'] = $user['user_telephone'];
                        $_SESSION['naissance'] = $user['user_birth'];
                        $_SESSION['creation'] = $user['creation_date'];
                        $_SESSION['bio'] = $user['user_bio'];
                        $_SESSION['identified'] = true;
                        header('Location: index.php');
                    }
                    else{
                        $_SESSION['alreadyExist'] = true;
                    }
                }
            }
        ?>

        <div class="container">
            <div class="rightLogin">
            </div>
            <div class="centerLogin">
                <div class="imgLogin">
                    <img src="img/melodiz.png" alt="logo" width="250px">
                </div>
                <div class="titleLogin">
                </div>
                <div id="mainAdding">
                    <div id="formAdding" class="shadow">
                        <h2>FORMULAIRE</h2>
                        <form action="register.php" method="post">
                            <div class="row">
                                <div class="col">
                                    <h4><span class="material-symbols-outlined">
                                    badge
                                    </span>&nbspPrénom</h4>
                                    <input type="text" class="form-control" name ="prenom">
                                </div>
                                <div class="col">
                                    <h4><span class="material-symbols-outlined">
                                    badge
                                    </span>&nbspNom</h4>
                                    <input type="text" class="form-control" name ="nom">
                                </div>
                            </div>
                            <br>
                            <div class="form-row">
                                <div class="form-group">
                                    <h4><span class="material-symbols-outlined">
                                    mail
                                    </span>&nbspEmail</h4>
                                    <input type="email" class="form-control" id="inputEmail4" name ="email">
                                </div>
                                <br>
                                <div class="form-group">
                                    <h4><span class="material-symbols-outlined">
                                    lock
                                    </span>&nbspMot de passe</h4>
                                    <input type="password" class="form-control" name="password" id="password1">
                                    <div class="form-check form-switch" id="ecarted">
                                    <input class="form-check-input" type="checkbox" role="switch" id="showPassword1" onchange="togglePasswordRe()">
                                        <label class="form-check-label" for="flexSwitchCheckChecked">Afficher votre mot de passe</label>
                                    </div>
                                    <h4><span class="material-symbols-outlined">
                                    lock
                                    </span>&nbspConfirmation Mot de passe</h4>
                                    <input type="password" class="form-control" id="password2" name="passwordConfirmed">
                                    <div class="form-check form-switch" id="ecarted">
                                        <input class="form-check-input" type="checkbox" role="switch" id="showPassword2" onchange="togglePasswordRe2()">
                                        <label class="form-check-label" for="flexSwitchCheckChecked">Afficher votre mot de passe</label>
                                    </div>
                                    <?php
                                        if(isset($_SESSION['passwordNotConfirmed']) && $_SESSION['passwordNotConfirmed']){
                                            echo '<div class="alert alert-danger erreur" role="alert">
                                            Les mots de passe ne correspondent pas
                                            </div>';
                                        }

                                    ?>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col">
                                    <h4><span class="material-symbols-outlined">
                                    call
                                    </span>&nbspNuméro de téléphone</h4>
                                    <input type="number" class="form-control" name = "telephone">
                                </div>
                            </div>
                            <?php
                                if(isset($_SESSION['erreurTelephone']) && $_SESSION['erreurTelephone']){
                                    echo '<div class="alert alert-danger erreur" role="alert">
                                    Votre numéro de téléphone est incorrect
                                    </div>';
                                }
                            ?>
                            <br>
                            <div class="row">
                                <div class="col">
                                    <h4><span class="material-symbols-outlined">
                                        calendar_month</span>&nbspDate de naissance</h4>
                                    <input type="date" class="form-control" name = "naissance">
                                </div>
                            </div>
                            <?php
                                        if(isset($_SESSION['ageNotValid']) && $_SESSION['ageNotValid']){
                                            echo '<div class="alert alert-danger erreur" role="alert">
                                            Vous devez avoir entre 5 et 100 ans
                                            </div>';
                                        }

                                    ?>
                            <br>
                            <button type="submit" class="btn btn-primary colorRed" name ="envoyer">S'Inscrire</button>
                        </form>
                        <br>
                        <div class="lien">
                            <a href="login.php">Déjà inscrit ?</a>
                        </div>
                        <?php
                        if(isset($_SESSION['alreadyExist']) && $_SESSION['alreadyExist']){
                            echo '<div class="alert alert-danger erreur" role="alert">
                            Il semblerait que votre compte existe déjà
                            </div>';
                        }
                        ?>
                    </div>
                </div>
            <div class="leftLogin">
            </div>
        </div>
        
    </body>
</html>