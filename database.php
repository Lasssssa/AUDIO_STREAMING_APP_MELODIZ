<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    require_once("constants.php");

    function dbConnect(){
        $dsn = 'pgsql:dbname='.DB_NAME.';host='.DB_SERVER.';port='.DB_PORT;
        $user = DB_USER;
        $password = DB_PASSWORD;
        try {
            $dbConnect = new PDO($dsn, $user, $password);
        } catch (PDOException $e) {
            echo 'Connexion échouée : ' . $e->getMessage();
        }
        return $dbConnect;
    }

    function isValidUser($email, $dbConnection){
        try{
            $query = 'SELECT * FROM utilisateur WHERE user_mail  = :email';
            $statement = $dbConnection->prepare($query);
            $statement->bindParam(':email', $email);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo $e->getMessage();
        }
        if(count($result) > 0){
            return true;
        }else{
            return false;
        }
    }
    
    function getEncryptedPassword($email,$dbConnection){
        try{
            $query = 'SELECT * FROM utilisateur WHERE user_mail = :email';
            $statement = $dbConnection->prepare($query);
            $statement->bindParam(':email', $email);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo $e->getMessage();
        }
        if($result!=null){
            return $result[0]['user_password'];
        }else{
            return null;
        }
    }

    function updateAccount($id, $prenom, $nom, $email,$date_naissance, $telephone, $db){
        try{
            $query = 'UPDATE utilisateur SET user_firstname = :prenom, user_lastname = :nom, user_mail = :email, user_birth = :date_naissance, user_telephone = :telephone WHERE id = :id';
            $statement = $db->prepare($query);
            $statement->bindParam(':id', $id);
            $statement->bindParam(':prenom', $prenom);
            $statement->bindParam(':nom', $nom);
            $statement->bindParam(':email', $email);
            $statement->bindParam(':date_naissance', $date_naissance);
            $statement->bindParam(':telephone', $telephone);
            $statement->execute();
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }

    function updatePassword($id,$passwd,$dbConnection){
        try{
            $query = 'UPDATE utilisateur SET user_password = :passwd WHERE id = :id';
            $statement = $dbConnection->prepare($query);
            $statement->bindParam(':id', $id);
            $statement->bindParam(':passwd', $passwd);
            $statement->execute();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    function getUser($email, $dbConnection){
        try{
            $query = 'SELECT * FROM utilisateur WHERE user_mail = :email';
            $statement = $dbConnection->prepare($query);
            $statement->bindParam(':email', $email);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo $e->getMessage();
        }
        return $result;
    }

    function addUser($db, $prenom, $nom, $email, $password, $telephone, $creation_date, $birthdate){
        try{
            $query = 'SELECT * FROM utilisateur WHERE user_mail = :email';
            $statement = $db->prepare($query);
            $statement->bindParam(':email', $email);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo $e->getMessage();
        }
        if(count($result) > 0){
            return false;
        }else{
            try{
                $query= 'INSERT INTO utilisateur (user_firstname, user_lastname, user_mail, user_password, user_telephone, creation_date, user_birth) VALUES (:prenom, :nom, :email, :password, :telephone, :creation_date, :birthdate)';
                $statement = $db->prepare($query);
                $statement->bindParam(':prenom', $prenom);
                $statement->bindParam(':nom', $nom);
                $statement->bindParam(':email', $email);
                $statement->bindParam(':password', $password);
                $statement->bindParam(':telephone', $telephone);
                $statement->bindParam(':creation_date', $creation_date);
                $statement->bindParam(':birthdate', $birthdate);
                $statement->execute();
                return true;
            }catch(Exception $e){
                echo $e->getMessage();
                return false;
            }
        }
    }
    function dbGetLastEcoute($db,$id){
        try{
            $id_PlaylistLike = getLikePlaylist($db,$id);
            $query = 'SELECT DISTINCT h.*, m.*, a.*, ar.*, 
                CASE WHEN pc.playlist_id IS NOT NULL THEN TRUE ELSE FALSE END AS isliked
                FROM historique h
                JOIN music m ON h.music_id = m.music_id
                JOIN album a ON a.id_album = m.id_album
                JOIN artiste ar ON ar.artiste_id = a.artiste_id
                LEFT JOIN (
                    SELECT DISTINCT music_id, playlist_id
                    FROM music_contenu
                    WHERE playlist_id = :playlist_id
                ) pc ON pc.music_id = m.music_id
                WHERE h.id = :id
                ORDER BY h.last_ecoute DESC
                LIMIT 10';
            $statement = $db->prepare($query);
            $statement->bindParam(':id', $id);
            $statement->bindParam(':playlist_id', $id_PlaylistLike);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo $e->getMessage();
        }
        return $result;
    }
    

    function dbGetMusicInfo($db,$id_music){
        try{
            $query = 'SELECT * FROM music m JOIN album a ON m.id_album = a.id_album JOIN artiste ar ON a.artiste_id = ar.artiste_id WHERE m.music_id = :id_music';
            $statement = $db->prepare($query);
            $statement->bindParam(':id_music', $id_music);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        }catch(Exception $e){
            echo $e->getMessage();
        }
        return $result;
    }

    function dbGetPlaylist($db,$id){
        try{
            $query = 'SELECT * FROM playlist p WHERE id = :id';
            $statement = $db->prepare($query);
            $statement->bindParam(':id', $id);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo $e->getMessage();
        }
        return $result;
    }

    function setUpUser($db,$id,$date){
        try{
            $boolMusic = true;
            $query = 'INSERT INTO playlist (playlist_name,playlist_creation,id,havepicture) VALUES (\'Titres likés\',:dateToday,:id,:boolMusic)';
            $statement = $db->prepare($query);
            $statement->bindParam(':dateToday', $date);
            $statement->bindParam(':id', $id);
            $statement->bindParam(':boolMusic', $boolMusic);
            $statement->execute();
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }

    function getLikePlaylist($dbConnection,$idPerso){
        try{
            $query = 'SELECT * from playlist WHERE id = :idPerso AND playlist_name = \'Titres likés\'';
            $statement = $dbConnection->prepare($query);
            $statement->bindParam(':idPerso', $idPerso);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo $e->getMessage();
        }
        return $result[0]['playlist_id'];
    }

    function dbAddLike($dbConnection, $idperso,$idMusic){
        $date = date("Y-m-d");
        $id_PlaylistLike = getLikePlaylist($dbConnection,$idperso);
        $isLiked = isLiked($dbConnection,$id_PlaylistLike,$idMusic);
        if($isLiked){
            try{
                $query = 'DELETE FROM music_contenu WHERE playlist_id = :id_PlaylistLike AND music_id = :idMusic';
                $statement = $dbConnection->prepare($query);
                $statement->bindParam(':id_PlaylistLike', $id_PlaylistLike);
                $statement->bindParam(':idMusic', $idMusic);
                $statement->execute();
            }catch(Exception $e){
                echo $e->getMessage();
            }
            try{
                $query = 'UPDATE music SET isliked = \'false\' WHERE music_id = :idMusic';
                $statement = $dbConnection->prepare($query);
                $statement->bindParam(':idMusic', $idMusic);
                $statement->execute();
            }catch(Exception $e){
                echo $e->getMessage();
            }
        }else{
            try{
                $date = date("Y-m-d");
                $query = 'INSERT INTO music_contenu (playlist_id,music_id,date_ajout) VALUES (:id_PlaylistLike,:idMusic,:dateAjout)';
                $statement = $dbConnection->prepare($query);
                $statement->bindParam(':id_PlaylistLike', $id_PlaylistLike);
                $statement->bindParam(':idMusic', $idMusic);
                $statement->bindParam(':dateAjout', $date);
                $statement->execute();
            }catch(Exception $e){
                echo $e->getMessage();
            }
            try{
                $query = 'UPDATE music SET isliked = \'true\' WHERE music_id = :idMusic';
                $statement = $dbConnection->prepare($query);
                $statement->bindParam(':idMusic', $idMusic);
                $statement->execute();
            }
            catch(Exception $e){
                echo $e->getMessage();
            }
        }
        return [$idMusic,!$isLiked];
    }

    function isLiked($dbConnection, $id_PlaylistLike,$idMusic){
        try{
            $query = 'SELECT * FROM music_contenu WHERE playlist_id = :id_PlaylistLike AND music_id = :idMusic';
            $statement = $dbConnection->prepare($query);
            $statement->bindParam(':id_PlaylistLike', $id_PlaylistLike);
            $statement->bindParam(':idMusic', $idMusic);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo $e->getMessage();
        }
        if(count($result) > 0){
            return true;
        }else{
            return false;
        }
    }

    function dbGetOnePlaylist($dbConnection,$idPlaylist,$idPerso){
        $id_titre_like = getLikePlaylist($dbConnection,$idPerso);
        try{
            $query = 'SELECT *, 
            CASE WHEN EXISTS (
                SELECT 1 FROM playlist a
                LEFT JOIN music_contenu ON a.playlist_id = music_contenu.playlist_id
                WHERE a.playlist_id = :idPlaylistLike AND music_contenu.music_id = music.music_id
            ) THEN 1 ELSE 0 END AS isliked
            FROM playlist a
            LEFT JOIN music_contenu ON a.playlist_id = music_contenu.playlist_id
            LEFT JOIN music ON music_contenu.music_id = music.music_id
            LEFT JOIN album ON music.id_album = album.id_album
            LEFT JOIN artiste ON album.artiste_id = artiste.artiste_id
            LEFT JOIN utilisateur ON a.id = utilisateur.id
            WHERE a.playlist_id = :idPlaylist AND a.id = :idPerso';
            $statement = $dbConnection->prepare($query);
            $statement->bindParam(':idPlaylist', $idPlaylist);
            $statement->bindParam(':idPerso', $idPerso);
            $statement->bindParam(':idPlaylistLike', $id_titre_like);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo $e->getMessage();
        }
        return $result;
    }

    function dbGetOneArtist($dbconnection, $id){
        try{
            $query = 'SELECT * FROM artiste JOIN album ON artiste.artiste_id = album.artiste_id WHERE artiste.artiste_id = :id';
            $statement = $dbconnection->prepare($query);
            $statement->bindParam(':id', $id);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo $e->getMessage();
        }
        return $result;
    }

    function getTop3Music($db,$id_artiste){
        try{
            $query = 'SELECT * FROM music JOIN album ON music.id_album = album.id_album JOIN artiste ON album.artiste_id = artiste.artiste_id WHERE artiste.artiste_id = :id_artiste LIMIT 3';
            $statement = $db->prepare($query);
            $statement->bindParam(':id_artiste', $id_artiste);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo $e->getMessage();
        }
        return $result;
    }

    function dbGetAlbumWithId($db,$id_album,$id_perso){
        try{
            $playlistLike = getLikePlaylist($db,$id_perso);
            $query = 'SELECT m.*, album.*, artiste.*,
            CASE WHEN pc.playlist_id IS NOT NULL THEN TRUE ELSE FALSE END AS isliked
                FROM music m
                JOIN album ON m.id_album = album.id_album
                JOIN artiste ON album.artiste_id = artiste.artiste_id
                LEFT JOIN (
                    SELECT DISTINCT music_id, playlist_id
                    FROM music_contenu
                    WHERE playlist_id = :playlist_id
                ) pc ON pc.music_id = m.music_id
                WHERE album.id_album = :id_album
                ORDER BY m.music_title ASC;
                ';
            $statement = $db->prepare($query);
            $statement->bindParam(':id_album', $id_album);
            $statement->bindParam(':playlist_id', $playlistLike);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo $e->getMessage();
        }
        return $result;
    }

    function dbGetPlaylistWithMusic($dbConnection, $id_music,$id_perso){
        try{
            $query = 'SELECT p.*, CASE WHEN mc.music_id IS NULL THEN false ELSE true END AS isinplaylist
            FROM utilisateur u
            JOIN playlist p ON u.id = p.id
            LEFT JOIN music_contenu mc ON p.playlist_id = mc.playlist_id AND mc.music_id = :id_music
            WHERE u.id = :id';
            $statement = $dbConnection->prepare($query);
            $statement->bindParam(':id_music', $id_music);
            $statement->bindParam(':id', $id_perso);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo $e->getMessage();
        }
        return [$result,$id_music];
    }

    function dbAddToPlaylist($db,$id_music,$id_playlist,$id_perso){
        $isInPlaylist = isInPlaylist($db,$id_music,$id_playlist);
        // $playlistLike = getLikePlaylist($db,$id_perso);
        //A MODIFIER
        if($isInPlaylist){
            try{
                $query = 'DELETE FROM music_contenu WHERE music_id = :id_music AND playlist_id = :id_playlist';
                $statement = $db->prepare($query);
                $statement->bindParam(':id_music', $id_music);
                $statement->bindParam(':id_playlist', $id_playlist);
                $statement->execute();
            }catch(Exception $e){
                echo $e->getMessage();
            }
        }else{
            try{
                $date = date("Y-m-d");
                $query = 'INSERT INTO music_contenu (music_id,playlist_id,date_ajout) VALUES (:id_music,:id_playlist,:date_ajout)';
                $statement = $db->prepare($query);
                $statement->bindParam(':id_music', $id_music);
                $statement->bindParam(':id_playlist', $id_playlist);
                $statement->bindParam(':date_ajout', $date);
                $statement->execute();
            }catch(Exception $e){
                echo $e->getMessage();
            }
            
        }
        return [$id_music,$id_playlist,!$isInPlaylist];
    }

    function isInPlaylist($db,$id_music,$id_playlist){
        try{
            $query = 'SELECT * FROM music_contenu WHERE music_id = :id_music AND playlist_id = :id_playlist';
            $statement = $db->prepare($query);
            $statement->bindParam(':id_music', $id_music);
            $statement->bindParam(':id_playlist', $id_playlist);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo $e->getMessage();
        }
        if(count($result) > 0){
            return true;
        }else{
            return false;
        }
    }
    
    function dbDeleteMusic($db,$id_music,$id_playlist){
        try{
            $query = 'DELETE FROM music_contenu WHERE music_id = :id_music AND playlist_id = :id_playlist';
            $statement = $db->prepare($query);
            $statement->bindParam(':id_music', $id_music);
            $statement->bindParam(':id_playlist', $id_playlist);
            $statement->execute();
        }catch(Exception $e){
            echo $e->getMessage();
        }
        return $id_playlist;
    }

    function dbGetOneMusic($db,$id_music,$id_perso){
        $id_PlaylistLike = getLikePlaylist($db,$id_perso);
        try{
            $query = 'SELECT DISTINCT music.*, album.*, artiste.*,
            (CASE WHEN EXISTS (SELECT 1 FROM music_contenu WHERE music_contenu.music_id = music.music_id AND music_contenu.playlist_id = :id_playlist)
                  THEN 1 ELSE 0 END) AS isliked
            FROM music
            JOIN album ON music.id_album = album.id_album
            JOIN artiste ON album.artiste_id = artiste.artiste_id
            WHERE music.music_id = :id_music';
            $statement = $db->prepare($query);
            $statement->bindParam(':id_music', $id_music);
            $statement->bindParam(':id_playlist', $id_PlaylistLike);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo $e->getMessage();
        }
        return $result;
    }

    function dbUpdateHistory($db, $idMusic, $idPerso) {
        try {
            $query = 'SELECT * FROM historique WHERE music_id = :id_music AND id = :idPerso';
            $statement = $db->prepare($query);
            $statement->bindParam(':id_music', $idMusic);
            $statement->bindParam(':idPerso', $idPerso);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    
            if (count($result) > 0) {
                $date = date("Y-m-d H:i:s");
                $query = 'UPDATE historique SET last_ecoute = :date_ajout WHERE music_id = :id_music AND id = :idPerso';
                $statement = $db->prepare($query);
                $statement->bindParam(':id_music', $idMusic);
                $statement->bindParam(':idPerso', $idPerso);
                $statement->bindParam(':date_ajout', $date);
                $statement->execute();
            } else {
                $date = date("Y-m-d H:i:s");
                $query = 'INSERT INTO historique (music_id, id, last_ecoute) VALUES (:id_music, :idPerso, :date_ajout)';
                $statement = $db->prepare($query);
                $statement->bindParam(':id_music', $idMusic);
                $statement->bindParam(':idPerso', $idPerso);
                $statement->bindParam(':date_ajout', $date);
                $statement->execute();
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    
        return $idMusic;
    }

    function dbGetUser($db,$id){
        try{
            $query = 'SELECT * FROM utilisateur WHERE id = :id';
            $statement = $db->prepare($query);
            $statement->bindParam(':id', $id);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo $e->getMessage();
        }
        return $result;
    }

    function getProfilPicture($lastname_firstname){
        //Récupérer une image de profil dans le dossier photo_profil
        $dir = 'photo_profil';
        $files = scandir($dir);
        $img = null;
        foreach($files as $file){
            if($file != '.' && $file != '..'){
                $name = explode('.',$file)[0];
                if($name == $lastname_firstname){
                    $img = $file;
                }

            }
        }
        return $img;
    }

    function dbRechercheMusic($db, $recherche,$idPerso){
        $id_playlist = getLikePlaylist($db,$idPerso);
        $recherche = '%'.$recherche.'%';
        try{
            $query = 'SELECT DISTINCT music.*, album.*, artiste.*, 
                CASE WHEN music.music_id IN (
                    SELECT music_id FROM music_contenu WHERE playlist_id = :id_playlistlike
                ) THEN TRUE ELSE FALSE END AS isliked
            FROM music
            JOIN album ON music.id_album = album.id_album
            JOIN artiste ON album.artiste_id = artiste.artiste_id
            WHERE LOWER(music.music_title) LIKE LOWER(:recherche)';
            $statement = $db->prepare($query);
            $statement->bindParam(':recherche', $recherche);
            $statement->bindParam(':id_playlistlike', $id_playlist);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo $e->getMessage();
        }
        return $result;
    }

    function dbRechercheAlbum($db,$recherche){
        $recherche = '%'.$recherche.'%';
        try{
            $query = 'SELECT DISTINCT album.*, artiste.* FROM album
            JOIN artiste ON album.artiste_id = artiste.artiste_id
            WHERE LOWER(album.album_title) LIKE LOWER(:recherche)';
            $statement = $db->prepare($query);
            $statement->bindParam(':recherche', $recherche);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo $e->getMessage();
        }
        return $result;
    }

    function dbRechercheArtiste($db,$recherche){
        $recherche = '%'.$recherche.'%';
        try{
            $query = 'SELECT DISTINCT artiste.*, 
            (SELECT COUNT(*) FROM album WHERE album.artiste_id = artiste.artiste_id) AS albumcount
            FROM artiste
            WHERE LOWER(artiste.artiste_name) LIKE LOWER(:recherche) OR LOWER(artiste.artiste_lastname) LIKE LOWER(:recherche)';
            $statement = $db->prepare($query);
            $statement->bindParam(':recherche', $recherche);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo $e->getMessage();
        }
        return $result;
    }

    function dbAddPlaylist($db,$id_user,$name){
        $dateAdd = date("Y-m-d H:i:s");
        try{
            $query = 'SELECT * FROM playlist WHERE id = :id_user AND playlist_name = :name';
            $statement = $db->prepare($query);
            $statement->bindParam(':id_user', $id_user);
            $statement->bindParam(':name', $name);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo $e->getMessage();
        }
        if(count($result) == 0){
            try{
                $query = 'INSERT INTO playlist (id, playlist_name, playlist_creation,havepicture) VALUES (:id_user, :namePlaylist, :dateAjout,\'false\')';
                $statement = $db->prepare($query);
                $statement->bindParam(':id_user', $id_user);
                $statement->bindParam(':namePlaylist', $name);
                $statement->bindParam(':dateAjout', $dateAdd);
                $statement->execute();
            }catch(Exception $e){
                echo $e->getMessage();
            }
        }else{
            return false;
        }
        return true;
    }
?>