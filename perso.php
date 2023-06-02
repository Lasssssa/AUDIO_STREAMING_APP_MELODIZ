<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@40,400,0,0" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
<title>Profil</title>





<body>
<div class="row navabarPosition autre" style="background-color: #ef2d2d">
        <div class="col-12"><br></div>
        <div class="col-1">
            <a class="navbar-brand centerElement" href="#"><span class="material-symbols-outlined">
                home
            </span></a>
        </div>
        <div class="col-3 text-center"><h3 class="">Votre compte</h3></div>
        <div class="col-4">
            <div class="input-group mb-3">
                <input type="text" class="form-control">
                <button class="btn btn-secondary" type="submit" id="">
                    <span class="material-symbols-outlined">
                        search
                    </span>
                </button>
            </div>
        </div>
        <div class="col-2"></div>
        <div class="col-2">
        
            <a class="nav-link dropdown-toggle hovered navbar-brand text-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Prénom Nom
            </a>
            <div id="dropD">
                <ul class="dropdown-menu dropdown-menu-dark">
                     <li><a class="dropdown-item" href="infoEnseignant.php">Compte</a></li>
                     <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="../loginPage.php">Deconnexion</a></li>
                </ul>
            </div>
        

        </div>
    </div>




    <br>
    <h3 class="text-center">Votre compte</h3>
    <!-- <img src="account_circle.svg" alt="image" class="" height=200   > -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div class="row ">
        <img src="account_circle.svg" alt="image" class="" height=200   >
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
                <!-- <div class="col-4"></div> -->
                <div class="text-center">
                    <button type="submit" class="btn btn-danger">Modifier</button>
                </div>
                <!-- <div class="col-4"></div> -->
                <!-- <button type="submit" class="btn btn-danger">Modifier</button> -->
            </div>
        </div>
        <div class="col-1 margin"></div>
        
        

    </div>
        
    </form>
</body>
</html>

    