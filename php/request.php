<?php
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

    if($type=='POST'){
        // parse_str(file_get_contents('php://input'), $_POST);
        if(isset($_POST['request']) && $_POST['request']=='upDateHistory'){
            // echo json_encode("coucou");
            // echo $_PUT['idMusic'];
            // echo $_PUT['idPerso'];
            $playlist = dbUpdateHistory($dbConnection, $_POST['idMusic'],$_POST['idPerso']);
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
    
?>
