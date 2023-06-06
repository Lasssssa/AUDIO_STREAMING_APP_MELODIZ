<?php

    session_start(); 

    require_once("../database.php");
    
    //Connexion avec la base de données
    $dbConnection = dbConnect();

    //Type de la requête
    $type = $_SERVER['REQUEST_METHOD'];

    //REQUETE POUR RECUPERER LES DERNIERES ECOUTES
    if(isset($_GET['request']) && $_GET['request'] == 'last_ecoute'){
        $last_ecoute = dbGetLastEcoute($dbConnection, $_GET['id_perso']);
        echo json_encode($last_ecoute);
    }

    //REQUETE POUR RECUPERER LES PLAYLIST DE L'UTILISATEUR
    if(isset($_GET['request']) && $_GET['request'] == 'playlist'){
        $playlist = dbGetPlaylist($dbConnection, $_GET['id_perso']);
        echo json_encode($playlist);
    }

    //REQUETE POUR LIKER UNE MUSIQUE 
    if(isset($_POST['request']) && $_POST['request']=='likeMusic'){
        $idMusic = dbAddLike($dbConnection, $_POST['idPerso'], $_POST['idMusic']);
        echo json_encode($idMusic);
    }

    //REQUETE POUR RECUPERER UNE PLAYLIST EN PARTICULIER
    if(isset($_GET['request']) && $_GET['request'] == 'getOnePLaylist'){
        $playlist = dbGetOnePlaylist($dbConnection, $_GET['idPlaylist'],$_GET['idPerso']);
        echo json_encode($playlist);
    }

    // $playlist = dbGetOnePlaylist($dbConnection, 8,1);
    //     echo json_encode($playlist);

    //REQUETE POUR RECUPERER UN ARTISTE EN PARTICULIER
    if(isset($_GET['request']) && $_GET['request'] == 'getOneArtist'){
        $artist = dbGetOneArtist($dbConnection, $_GET['idArtist']);
        echo json_encode($artist);
    }

    //REQUETE POUR RECUPERER LE TOP 3 DES MUSIQUES D'UN ARTISTE
    if(isset($_GET['request']) && $_GET['request'] == 'getTop3'){
        $music = getTop3Music($dbConnection, $_GET['idArtist']);
        echo json_encode($music);
    }

    //REQUETE POUR RECUPERER UN ALBUM EN PARTICULIER
    if(isset($_GET['request']) && $_GET['request'] == 'getOneAlbum'){
        $album = dbGetAlbumWithId($dbConnection, $_GET['idAlbum'], $_GET['idPerso']);
        echo json_encode($album);
    }

    //REQUETE POUR RECUPERER LES PLAYLIST LIÉ A UNE MUSIQUE
    if(isset($_GET['request']) && $_GET['request'] == 'playlistWithMusic'){
        $playlist = dbGetPlaylistWithMusic($dbConnection, $_GET['idMusic'],$_GET['id_perso']);
        echo json_encode($playlist);
    }

    //REQUETE POUR AJOUTER UNE MUSIQUE A UNE PLAYLIST
    if(isset($_POST['request']) && $_POST['request'] == 'addToPlaylist'){
        $idMusic = dbAddToPlaylist($dbConnection,$_POST['idMusic'], $_POST['idPlaylist'], $_POST['idPerso']);
        echo json_encode($idMusic);
    }

    //REQUETE POUR RECUPERER LES INFOS D'UNE MUSIQUE
    if(isset($_GET['request']) && $_GET['request']=='music'){
        $music = dbGetMusicInfo($dbConnection, $_GET['idMusic']);
        echo json_encode($music);
    }

    // REQUETE POUR DELETE UNE MUSIQUE D'UNE PLAYLIST
    if($type == 'DELETE' && isset($_GET['request']) && $_GET['request']=='deleteMusicFromPlaylist'){
        $music = dbDeleteMusic($dbConnection, $_GET['idMusic'], $_GET['idPlaylist']);
        echo json_encode($music);
    }

    //REQUETE POUR RECUPERER UNE MUSIQUE EN PARTICULIER
    if(isset($_GET['request']) && $_GET['request']=='playMusic'){
        $music = dbGetOneMusic($dbConnection, $_GET['idMusic'], $_GET['idPerso']);
        echo json_encode($music);
    }

    parse_str(file_get_contents('php://input'), $_PUT);

    //REQUETE POUR UPDATE L'HISTORIQUE
    if($type=='PUT' && isset($_PUT['request']) && $_PUT['request']=='upDateHistory'){
        $playlist = dbUpdateHistory($dbConnection, $_PUT['idMusic'],$_PUT['idPerso']);
        echo json_encode($playlist);
    }

    //REQUETE POUR RECUPERER UN USER AVEC SON ID
    if(isset($_GET['request']) && $_GET['request']=='getCurrentUser'){
        $user = dbGetUser($dbConnection, $_GET['idPerso']);
        echo json_encode($user);
    }

    //REQUETE POUR RECUPERER TOUTES LES MUSIQUES CORRESPONDANT A UNE RECHERCHE
    if(isset($_GET['request']) && $_GET['request']=='searchMusic'){
        $music = dbRechercheMusic($dbConnection, $_GET['recherche'],$_GET['idPerso']);
        echo json_encode($music);
    }

    //REQUETE POUR RECUPERER TOUS LES ALBUMS CORRESPONDANT A UNE RECHERCHE
    if(isset($_GET['request']) && $_GET['request']=='searchAlbum'){
        $album = dbRechercheAlbum($dbConnection, $_GET['recherche']);
        echo json_encode($album);
    }

    //REQUETE POUR RECUPERER TOUS LES ARTISTES CORRESPONDANT A UNE RECHERCHE
    if(isset($_GET['request']) && $_GET['request']=='searchArtist'){
        $artist = dbRechercheArtiste($dbConnection, $_GET['recherche']);
        echo json_encode($artist);
    }

    //REQUETE POUR AJOUER UNE PLAYLIST A UN COMPTE
    if(isset($_POST['request']) && $_POST['request'] == 'addPlaylist'){
        $addPlaylist = dbAddPlaylist($dbConnection, $_POST['idPerso'], $_POST['name']);
        echo json_encode($addPlaylist);
    }
    
    //REQUETE POUR DELETE UNE PLAYLIST
    if($type == 'DELETE' && isset($_GET['request']) && $_GET['request']=='deletePlaylist'){
        $isDeleted = dbDeletePlaylist($dbConnection, $_GET['id_playlist'], $_GET['id_user']);
        echo json_encode($isDeleted);
    }
    
    //REQUETE POUR MODIFIER UNE PLAYLIST
    if($type = 'PUT' && isset($_PUT['request']) && $_PUT['request'] == 'modifyPlaylist'){
        $modified = dbModifyPlaylist($dbConnection, $_PUT['id_playlist'],$_PUT['name'],$_PUT['id_user']);
        echo json_encode($modified);
    }

    //REQUETE POUR RECUPERER UN USER AVEC SON ID
    if(isset($_GET['request']) && $_GET['request']=='getUser'){
        $user = dbGetUser($dbConnection,$_GET['idPerso']);
        echo json_encode($user);
    }

    //REQUETE POUR MODIFIER UN COMPTE
    if ($_SERVER['REQUEST_METHOD'] == 'PUT' && isset($_PUT['request']) && $_PUT['request'] == 'modifyAccount') {
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

    //REQUETE POUR MODIFIER LE MOT DE PASSE
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

    //REQUETE POUR RECUPERER LE COMPTE D'UN AMI
    if(isset($_GET['request']) && $_GET['request'] == 'getAccountFriend'){
        $friend = dbGetUser($dbConnection, $_GET['idPerso']);
        echo json_encode($friend);
    }

    //REQUETE POUR RECUPERER LES DEMANDES D'AMIS EN ATTENTE
    if(isset($_GET['request']) && $_GET['request'] == 'demandeAmiAttente'){
        $demandeAmi = dbGetDemandeAmis($dbConnection, $_GET['idPerso']);
        echo json_encode($demandeAmi);
    }

    //REQUETE POUR RECUPERER LES DEMANDES D'AMIS ENVOYEES
    if(isset($_GET['request']) && $_GET['request'] == 'demandeAmiEnvoye'){
        $demandeAmi = dbGetDemandeAmisEnvoye($dbConnection, $_GET['idPerso']);
        echo json_encode($demandeAmi);
    }

    //REQUETE POUR RECUPERER LES AMIS
    if(isset($_GET['request']) && $_GET['request'] == 'ami'){
        $friend = dbGetFriends($dbConnection, $_GET['idPerso']);
        echo json_encode($friend);
    }

    //REQUETE POUR RECUPERER LES INFOS LIÉES AUX AMIS
    if(isset($_GET['request']) && $_GET['request'] == 'addFriendAccount'){
        $addFriend = dbRechercheFriend($dbConnection, $_GET['recherche']);
        echo json_encode($addFriend);
    }

    //REQUETE POUR AJOUTER UN AMI
    if(isset($_POST['request']) && $_POST['request']=='addFriendToList'){
        $addfriend = dbAddFriendToList($dbConnection, $_POST['idPerso'], $_POST['idFriend']);
        echo json_encode($addfriend);
    }

    //REQUETE POUR ACCEPTER UN AMI
    if(isset($_PUT['request']) && $_PUT['request'] == 'AcceptAmi'){
        $refuseAmi = dbAcceptAmi($dbConnection, $_PUT['idPerso'], $_PUT['idfriend']);
        echo json_encode($refuseAmi);
    }

    //REQUETE POUR REFUSER UN AMI
    if(isset($_PUT['request']) && $_PUT['request'] == 'RefuseAmi'){
        $refuseAmi = dbRefuseAmi($dbConnection, $_PUT['idPerso'], $_PUT['idfriend']);
        echo json_encode($refuseAmi);
    }

    //REQUETE POUR ANNULER UNE DEMANDE D'AMI
    if($type = 'DELETE' && isset($_GET['request']) && $_GET['request'] == 'annulerDemandeAmi'){
        $annulerDemandeAmi = dbAnnulerDemandeAmi($dbConnection, $_GET['idPerso'], $_GET['id_friend']);
        echo json_encode($annulerDemandeAmi);
    }

    //REQUETE POUR SUPPRIMER UN AMI
    if($type = 'DELETE' && isset($_GET['request']) && $_GET['request'] == 'deleteBothFriend'){
        $annulerDemandeAmi = dbDeleteAmiBothSide($dbConnection, $_GET['idPerso'], $_GET['id_friend']);
        echo json_encode($annulerDemandeAmi);
    }

    //REQUETE POUR RECUPERER LES INFORMATIONS D'UN COMPTE AMI
    if(isset($_GET['request']) && $_GET['request'] == 'accountOfFriend'){
        $accountFriend = dbGetAccountFriend($dbConnection, $_GET['idFriend']);
        echo json_encode($accountFriend);
    }

    //REQUETE POUR RECUPERER LES MESSAGES D'UN AMI
    if(isset($_GET['request']) && $_GET['request'] == 'getMessage'){
        $message = dbGetMessageBetweenFriend($dbConnection, $_GET['idPerso'], $_GET['idFriend']);
        echo json_encode($message);
    }

    //REQUETE POUR RECUPERER UNE PLAYLIST D'UN AMI
    if(isset($_GET['request']) && $_GET['request'] == 'onePlaylistFriend'){
        $playlist = dbGetPlaylistFriend($dbConnection,$_GET['idPlaylist']);
        echo json_encode($playlist);
    }

    //REQUETE POUR RECUPERER LE CHAT ENTRE DEUX PERSONNES
    if(isset($_GET['request']) && $_GET['request'] == 'chat'){
        $chat = dbGetAccountFriend($dbConnection,$_GET['idFriend']);
        echo json_encode($chat);
    }

    //REQUETE POUR AJOUTER UN MESSAGE
    if(isset($_POST['request']) && $_POST['request']=='addMessage'){
        $addMessage = dbAddMessage($dbConnection, $_POST['idPerso'], $_POST['idFriend'], $_POST['message']);
        echo json_encode($_POST['idFriend']);
    }

    //REQUETE POUR RECUPERER LA DERNIERE MUSIQUE ECOUTEE : 
    if(isset($_GET['request']) && $_GET['request']=='getLastMusique'){
        $lastmusic = dbGetLastMusic($dbConnection, $_GET['idPerso']);
        echo json_encode($lastmusic);
    }

    //REQUETE POUR RECUPERER LA RPOCHAINE MUSIQUE:
    if(isset($_GET['request']) && $_GET['request']=='getNextMusique'){
        $nextMusic = dbGetNextMusic($dbConnection, $_GET['idPerso']);
        echo json_encode($nextMusic);
    }

    //REQUETE POUR AJOUTER UNE MUSIQUE A LA FILE D'ATTENTE
    if(isset($_POST['request']) && $_POST['request']=='addMusicToQueue'){
        $addMusicToQueue = dbAddMusicToFile($dbConnection, $_POST['id_perso'], $_POST['id_music']);
        echo json_encode($addMusicToQueue);
    }

    //REQUETE POUR JOUER UN ALBUM
    if(isset($_GET['request']) && $_GET['request']=='playAlbum'){
        $playAlbum = dbPlayAlbum($dbConnection, $_GET['idPerso'], $_GET['idAlbum']);
        echo json_encode($playAlbum);
    }

    if(isset($_GET['request']) && $_GET['request']=='playPlaylist'){
        $playPlaylist = dbPlayPlaylist($dbConnection, $_GET['idPerso'], $_GET['idPlaylist']);
        echo json_encode($playPlaylist);
    }

    if(isset($_GET['request']) && $_GET['request'] == 'getGame'){
        $tenMusic = dbGetTenMusic($dbConnection);
        echo json_encode($tenMusic);
    }

?>
