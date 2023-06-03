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
            if(isset($_POST['submit_photo'])){
                move_uploaded_file($_FILES['photo_profil']['tmp_name'],"photo_profil/".$_SESSION['nom'].'_'.$_SESSION['prenom'].".png");
            }

        ?>
        <div id="infoPerso">
            <div class="titlePerso">
                <?php
                    require_once('database.php');
                    $db = dbConnect();
                    

                    if(isset($_POST['submit_password']) && isset($_POST['old_password']) && isset($_POST['new_password'])){
                        $user = getUser($_SESSION['email'],$db);
                        $encryptedPassword = getEncryptedPassword($_SESSION['email'], $db,$table);
                        if(password_verify($_POST['old_password'], $encryptedPassword)){
                            $new_encrypt = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                            $valid = updatePassword($_SESSION['id'], $new_encrypt, $db);
                            echo '<div class="alert alert-success" role="alert" id="deleteAnnee">
                                Mot de passe modifié avec succès !
                                </div>';
                        }else{
                            echo '<div class="alert alert-danger" role="alert" id="deleteAnnee">
                                Ancien mot de passe incorrect !
                                </div>';
                        }
                    }
                ?>
                <div id="mainAdding">
                    <div id="formAdding">
                    <?php
                    if(isset($_POST['submit_account']) && isset($_POST['prenom']) && isset($_POST['nom']) && isset($_POST['email']) && isset($_POST['date_naissance']) && isset($_POST['telephone'])){
                        $valid = updateAccount($_SESSION['id'], $_POST['prenom'], $_POST['nom'], $_POST['email'], $_POST['date_naissance'], $_POST['telephone'], $db);
                        if($valid){
                            $_SESSION['prenom'] = $_POST['prenom'];
                            $_SESSION['nom'] = $_POST['nom'];
                            $_SESSION['email'] = $_POST['email'];
                            $_SESSION['date_naissance'] = $_POST['date_naissance'];
                            $_SESSION['telephone'] = $_POST['telephone'];
                            echo '<div class="alert alert-success" role="alert" id="deleteAnnee">
                                Informations modifiées avec succès !
                                </div>';
                        }else{
                            echo '<div class="alert alert-danger" role="alert" id="deleteAnnee">
                                Erreur lors de la modification des informations !
                                </div>';
                        }
                    }
                    ?>  
                        <h2>INFORMATIONS PERSONNELLES</h2>
                        <br>
                        <div class="ppV2">
                            <?php
                                $name = $_SESSION['nom'].'_'.$_SESSION['prenom'];
                                $img = getProfilPicture($name);
                                if($img != null){
                                    echo '<img src="photo_profil/'.$img.'" class="pp" alt="photo de profil">';
                                }else{
                                    echo '<img src="photo_profil/profil_defaut.png" class="pp" alt="photo de profil">';
                                    }
                            ?>
                        </div>
                        <br>
                        <div class="import">
                            <h5>Importer votre photo de profil</h5>
                            <form action="perso.php" method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <input class="form-control" type="file" name="photo_profil" id="fileToUpload">
                                </div>
                                <button type="submit" class="btn btn-danger" name="submit_photo">Importer</button>
                            </form>
                            <br>
                        </div>
                        <form action="perso.php" method="post">
                            <div class="row">
                                <div class="col">
                                    <h4>Prénom</h4>
                                    <input class="form-control" name="prenom" type="text" value="<?php echo $_SESSION['prenom']; ?>" >
                                </div>
                                <div class="col">
                                    <h4>Nom</h4>
                                    <input class="form-control" name="nom" type="text" value="<?php echo $_SESSION['nom']; ?>">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col">
                                    <h4>Date de naissance</h4>
                                    <!-- Type date -->
                                    <input class="form-control" name="date_naissance" type="date" value="<?php echo date('Y-m-d', strtotime($_SESSION['naissance'])); ?>">
                                </div>
                                <div class="col">
                                    <h4>Age</h4>
                                    <!-- Input disable qui donne l'age -->
                                    <?php
                                    $today = date("Y-m-d");
                                    $diff = date_diff(date_create($_SESSION['naissance']), date_create($today));
                                    echo '<input class="form-control" type="text" value="'.$diff->format('%y ans').'"aria-label="Disabled input example" disabled >';
                                    ?>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col">
                                    <h4>Numéro de téléphone</h4>
                                    <input class="form-control" name="telephone" type="text" value="<?php echo $_SESSION['telephone']; ?>">
                                </div>
                            </div>
                            <br>
                            <div class="form-group">
                                <h4>Email</h4>
                                <input class="form-control" name="email" type="text" value="<?php echo $_SESSION['email']; ?>">
                            </div>
                            <br>
                            <div class="form-group">
                                <h4>Mot de passe</h4>
                                <input class="form-control" type="text" value="********" aria-label="Disabled input example" disabled>
                            </div>
                            <br>
                            <div class="center">
                                <button class="btn btn-danger" name="submit_account">Modifier</button>
                            </div>
                        </form>
                            <br>
                            <div class="accordion" id="accordionPanelsStayOpenExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="false" aria-controls="panelsStayOpen-collapseOne">
                                            MODIFICATION DU MOT DE PASSE
                                        </button>
                                    </h2>
                                    <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse">
                                        <div class="accordion-body">
                                            <form action="infoEnseignant.php" method="post">
                                                <div class="form-group">
                                                    <h4>Ancien mot de passe</h4>
                                                    <input type="password" class="form-control" id="password5" name="old_password">
                                                    <h4>Nouveau mot de passe</h4>
                                                    <input type="password" class="form-control" id="password2" name="new_password">
                                                </div>
                                                <br>
                                                <button type="submit" class="btn btn-primary coloredV2" name="submit_password">Modifier</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>  
                    </div>   
                </div> 
            </div>
        </div>


        
        <!-- <div id="container">
            <div class="formProfil">
                <h3 class="text-center ">Votre compte</h3>
                <div class="row">
                    <div class="d-flex justify-content-center align-items-center">
                        <img src="imgArtist/Balavoine.png" alt="image" class="" style="max-height:300px; max-width:300px;">
                    </div>
                    <div class="col-1"></div>
                    <div class="col-10">
                        <div class="row ">
                            <div class="col-4"></div>
                            <div class="col-4">
                                <div class="input-group mb-3">
                                <input type="file" class="form-control" id="inputGroupFile01" accept="image/png, image/jpeg">
                                </div>
                            </div>
                            <div class="col-4"></div>
                            <div class="col-5">
                                <div class="row">
                                    <div class="col-12">
                                        <p>Prénom</p>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <input type="text" class="form-control" name="prenom" value="Goustan">
                                    </div>
                                </div>
                            </div>
                            <div class="col-2"></div>
                            <div class="col-5">
                                <div class="row">
                                    <div class="col-12">
                                        <p>Nom</p>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <input type="text" class="form-control" name="prenom" value="Sermon">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 ">
                                <p>Email</p>
                            </div>
                            <div class="col-12 mb-3">
                                <input type="email" class="form-control" name="prenom" value="goustan.sermon@gmail.com">
                            </div>
                            <div class="col-12">
                                <p>Télephone</p>
                            </div>
                            <div class="col-12 mb-3">
                                <input type="number" class="form-control" name="prenom" value="0707070707">
                            </div>
                            <div class="col-5">
                                <div class="row">
                                    <div class="col-12">
                                        <p>Date de naissance</p>
                                    </div>
                                    <div class="col-12">
                                        <input type="date" class="form-control" name="prenom" value="2000-01-01">
                                    </div>
                                </div>
                            </div>
                            <div class="col-2"></div>
                            <div class="col-5">
                                <div class="row">
                                    <div class="col-12">
                                        <p>Age</p>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <input type="number" class="form-control" name="prenom" value="21" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <p>Mot de passe</p>
                            </div>
                            <div class="col-12 mb-3">
                                <input type="password" class="form-control" name="prenom" value="********">
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-danger">Modifier</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-1 margin"></div>
                    
                </div>
            </div>
        </div> -->
    </body>
</html>

    