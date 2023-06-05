<?php

    session_start(); 
    // ini_set('display_errors', 1);
    // error_reporting(E_ALL);
    require_once("../database.php");

    $dbConnection = dbConnect();

    $type = $_SERVER['REQUEST_METHOD'];

    // $request = substr($_SERVER['PATH_INFO'], 1);
    // $request = explode('/', $request);
    // $requestRessource = array_shift($request);

    if(isset($_GET['request']) && $_GET['request'] == 'last_ecoute'){
        $last_ecoute = dbGetLastEcoute($dbConnection, $_GET['id_perso']);
        echo json_encode($last_ecoute);
    }
    if(isset($_GET['request']) && $_GET['request'] == 'playlist'){
        $playlist = dbGetPlaylist($dbConnection, $_GET['id_perso']);
        echo json_encode($playlist);
    }
    if(isset($_POST['request']) && $_POST['request']=='likeMusic'){
        $idMusic = dbAddLike($dbConnection, $_POST['idPerso'], $_POST['idMusic']);
        echo json_encode($idMusic);
    }

    if(isset($_GET['request']) && $_GET['request'] == 'getOnePLaylist'){
        $playlist = dbGetOnePlaylist($dbConnection, $_GET['idPlaylist'],$_GET['idPerso']);
        echo json_encode($playlist);
    }

    if(isset($_GET['request']) && $_GET['request'] == 'getOneArtist'){
        $artist = dbGetOneArtist($dbConnection, $_GET['idArtist']);
        echo json_encode($artist);
    }
    if(isset($_GET['request']) && $_GET['request'] == 'getTop3'){
        $music = getTop3Music($dbConnection, $_GET['idArtist']);
        echo json_encode($music);
    }
    if(isset($_GET['request']) && $_GET['request'] == 'getOneAlbum'){
        $album = dbGetAlbumWithId($dbConnection, $_GET['idAlbum'], $_GET['idPerso']);
        echo json_encode($album);
    }
    if(isset($_GET['request']) && $_GET['request'] == 'playlistWithMusic'){
        $playlist = dbGetPlaylistWithMusic($dbConnection, $_GET['idMusic'],$_GET['id_perso']);
        echo json_encode($playlist);
    }

    if(isset($_POST['request']) && $_POST['request'] == 'addToPlaylist'){
        $idMusic = dbAddToPlaylist($dbConnection,$_POST['idMusic'], $_POST['idPlaylist'], $_POST['idPerso']);
        echo json_encode($idMusic);
    }
    if(isset($_GET['request']) && $_GET['request']=='music'){
        $music = dbGetMusicInfo($dbConnection, $_GET['idMusic']);
        echo json_encode($music);
    }

    if($type == 'DELETE' && isset($_GET['request']) && $_GET['request']=='deleteMusicFromPlaylist'){
        // echo 'coucuo';
        $music = dbDeleteMusic($dbConnection, $_GET['idMusic'], $_GET['idPlaylist']);
        echo json_encode($music);
    }

    if(isset($_GET['request']) && $_GET['request']=='playMusic'){
        $music = dbGetOneMusic($dbConnection, $_GET['idMusic'], $_GET['idPerso']);
        echo json_encode($music);
    }

    parse_str(file_get_contents('php://input'), $_PUT);

    if($type=='PUT'){
        // parse_str(file_get_contents('php://input'), $_POST);
        if(isset($_PUT['request']) && $_PUT['request']=='upDateHistory'){
            // echo json_encode("coucou");
            // echo $_PUT['idMusic'];
            // echo $_PUT['idPerso'];
            $playlist = dbUpdateHistory($dbConnection, $_PUT['idMusic'],$_PUT['idPerso']);
            echo json_encode($playlist);
        }
    }

    if(isset($_GET['request']) && $_GET['request']=='getCurrentUser'){
        $user = dbGetUser($dbConnection, $_GET['idPerso']);
        echo json_encode($user);
    }

    if(isset($_GET['request']) && $_GET['request']=='searchMusic'){
        $music = dbRechercheMusic($dbConnection, $_GET['recherche'],$_GET['idPerso']);
        echo json_encode($music);
    }
    if(isset($_GET['request']) && $_GET['request']=='searchAlbum'){
        $album = dbRechercheAlbum($dbConnection, $_GET['recherche']);
        echo json_encode($album);
    }
    if(isset($_GET['request']) && $_GET['request']=='searchArtist'){
        $artist = dbRechercheArtiste($dbConnection, $_GET['recherche']);
        echo json_encode($artist);
    }
    if(isset($_POST['request']) && $_POST['request'] == 'addPlaylist'){
        $addPlaylist = dbAddPlaylist($dbConnection, $_POST['idPerso'], $_POST['name']);
        echo json_encode($addPlaylist);
    }
    
    if($type == 'DELETE' && isset($_GET['request']) && $_GET['request']=='deletePlaylist'){
        // echo 'coucuo';
        $isDeleted = dbDeletePlaylist($dbConnection, $_GET['id_playlist'], $_GET['id_user']);
        echo json_encode($isDeleted);
    }

    if($type = 'PUT'){
        if (isset($_PUT['request']) && $_PUT['request'] == 'modifyPlaylist') {
            $modified = dbModifyPlaylist($dbConnection, $_PUT['id_playlist'],$_PUT['name'],$_PUT['id_user']);
            echo json_encode($modified);
        }
    }

    if(isset($_GET['request']) && $_GET['request']=='getUser'){
        $user = dbGetUser($dbConnection,$_GET['idPerso']);
        echo json_encode($user);
    }
    
    // echo json_encode($_PUT);

    if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_PUT['request']) && $_PUT['request'] == 'modifyAccount') {
        $name = $_PUT['name'];
        $lastname = $_PUT['lastname'];
        $email = $_PUT['email'];
        $idPerso = $_PUT['idPerso'];
        $birthdate = $_PUT['birthdate'];
        $telephone = $_PUT['telephone'];

        $age = date_diff(date_create($birthdate), date_create('today'))->y;
        if($name == '' || $lastname == '' || $email == '' || $idPerso == '' || $birthdate == '' || $telephone == '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($telephone) != 10 || strlen($birthdate) != 10 || $age < 5 || $age > 100 ){
            echo json_encode([false,[]]);
        }else{
            $modified = dbModifyAccount($dbConnection, $name, $lastname, $email, $idPerso, $birthdate, $telephone);
            echo json_encode([$modified,['user_firstname'=>$name,'user_lastname'=>$lastname]]);
            $user = getUser($email,$dbConnection);
            $_SESSION['email'] = $user['user_mail'];
            $_SESSION['nom'] = $user['user_lastname'];
            $_SESSION['prenom'] = $user['user_firstname'];
            $_SESSION['id'] = $user['id'];
            $_SESSION['telephone'] = $user['user_telephone'];
            $_SESSION['naissance'] = $user['user_birth'];
            $_SESSION['creation'] = $user['creation_date'];
            $_SESSION['bio'] = $user['user_bio'];
        }
    }

    if( $type = 'PUT' && isset($_PUT['request']) && $_PUT['request'] == 'modifyPassword'){
        $hash = dbGetPassword($dbConnection, $_PUT['id']);
        $goodPassword = password_verify($_PUT['password1'], $hash);
        if($goodPassword){
            $modified = dbModifyPassword($dbConnection, $_PUT['id'], password_hash($_PUT['password2'], PASSWORD_DEFAULT));
            echo json_encode($modified);
        }
        else{
            echo json_encode(false);
        }
    }

    if(isset($_GET['request']) && $_GET['request'] == 'getAccountFriend'){
        $friend = dbGetUser($dbConnection, $_GET['idPerso']);
        echo json_encode($friend);
    }

    if(isset($_GET['request']) && $_GET['request'] == 'demandeAmiAttente'){
        $demandeAmi = dbGetDemandeAmis($dbConnection, $_GET['idPerso']);
        echo json_encode($demandeAmi);
    }

    if(isset($_GET['request']) && $_GET['request'] == 'demandeAmiEnvoye'){
        $demandeAmi = dbGetDemandeAmisEnvoye($dbConnection, $_GET['idPerso']);
        echo json_encode($demandeAmi);
    }

    if(isset($_GET['request']) && $_GET['request'] == 'ami'){
        $friend = dbGetFriends($dbConnection, $_GET['idPerso']);
        echo json_encode($friend);
    }

    if(isset($_GET['request']) && $_GET['request'] == 'addFriendAccount'){
        $addFriend = dbRechercheFriend($dbConnection, $_GET['recherche']);
        echo json_encode($addFriend);
    }
    if(isset($_POST['request']) && $_POST['request']=='addFriendToList'){
        $addfriend = dbAddFriendToList($dbConnection, $_POST['idPerso'], $_POST['idFriend']);
        echo json_encode($addfriend);
    }

    if(isset($_PUT['request']) && $_PUT['request'] == 'AcceptAmi'){
        $refuseAmi = dbAcceptAmi($dbConnection, $_PUT['idPerso'], $_PUT['idfriend']);
        echo json_encode($refuseAmi);
    }

    if(isset($_PUT['request']) && $_PUT['request'] == 'RefuseAmi'){
        $refuseAmi = dbRefuseAmi($dbConnection, $_PUT['idPerso'], $_PUT['idfriend']);
        echo json_encode($refuseAmi);
    }
    if($type = 'DELETE' && isset($_GET['request']) && $_GET['request'] == 'annulerDemandeAmi'){
        $annulerDemandeAmi = dbAnnulerDemandeAmi($dbConnection, $_GET['idPerso'], $_GET['id_friend']);
        echo json_encode($annulerDemandeAmi);
    }
    if($type = 'DELETE' && isset($_GET['request']) && $_GET['request'] == 'deleteBothFriend'){
        $annulerDemandeAmi = dbDeleteAmiBothSide($dbConnection, $_GET['idPerso'], $_GET['id_friend']);
        echo json_encode($annulerDemandeAmi);
    }

    if(isset($_GET['request']) && $_GET['request'] == 'accountOfFriend'){
        $accountFriend = dbGetAccountFriend($dbConnection, $_GET['idFriend']);
        echo json_encode($accountFriend);
    }
    

    
    // $name = $_POST['name'];
        // $id_playlist = $_POST['id_playlist'];
        // $id_user = $_POST['id_user'];
    
        // if (isset($_FILES['image'])) {
        //     // Traitement du fichier ici
        //     $file = $_FILES['image'];
        //     // ...
        //     $destinationPath = '../playlist/';
          
        //     // Déplacer le fichier vers le dossier de destination
        //     $targetPath = $destinationPath . $file['name'];
        //     move_uploaded_file($file['tmp_name'], $targetPath);
          
        //     // Répondre avec un message de succès ou autre information
        //     echo json_encode(true);
        // }else{
        //     // Répondre avec un message d'erreur ou autre information
        //     echo json_encode(false);
        // }
        

?>
