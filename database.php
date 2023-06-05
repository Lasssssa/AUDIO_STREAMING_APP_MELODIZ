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
                $chemin = "photo_profil/profil_defaut.png";
                $query= 'INSERT INTO utilisateur (user_firstname, user_lastname, user_mail, user_password, user_telephone, creation_date, user_birth,user_chemin) VALUES (:prenom, :nom, :email, :password, :telephone, :creation_date, :birthdate,:chemin)';
                $statement = $db->prepare($query);
                $statement->bindParam(':prenom', $prenom);
                $statement->bindParam(':nom', $nom);
                $statement->bindParam(':email', $email);
                $statement->bindParam(':password', $password);
                $statement->bindParam(':telephone', $telephone);
                $statement->bindParam(':creation_date', $creation_date);
                $statement->bindParam(':birthdate', $birthdate);
                $statement->bindParam(':chemin', $chemin);
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
            $query = 'INSERT INTO playlist (playlist_name,playlist_creation,id,playlist_picture) VALUES (\'Titres Likés\',:dateToday,:id,:chemin)';
            $statement = $db->prepare($query);
            $statement->bindParam(':dateToday', $date);
            $statement->bindParam(':id', $id);
            $chemin = "playlist/like.png";
            $statement->bindParam(':chemin', $chemin);
            $statement->execute();
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }

    function getLikePlaylist($dbConnection,$idPerso){
        try{
            $query = 'SELECT * from playlist WHERE id = :idPerso AND playlist_name = \'Titres Likés\'';
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

    function clearHistory($db, $personId) {
        // Requête SQL pour compter le nombre de musiques pour la personne donnée
        $countSql = "SELECT COUNT(*) as total FROM historique WHERE id = :personId";
        $countStmt = $db->prepare($countSql);
        $countStmt->bindParam(':personId', $personId);
        $countStmt->execute();
        $countResult = $countStmt->fetch(PDO::FETCH_ASSOC);
    
        $totalMusics = intval($countResult['total']);
    
        if ($totalMusics > 10) {
            // Requête SQL pour sélectionner la musique la plus ancienne de la personne donnée
            $sql = "SELECT * FROM historique WHERE id = :personId ORDER BY last_ecoute ASC LIMIT 1";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':personId', $personId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($result) {
                // Supprimer la musique de la table history
                $deleteSql = "DELETE FROM historique WHERE music_id = :musicId";
                $deleteStmt = $db->prepare($deleteSql);
                $deleteStmt->bindParam(':musicId', $result['music_id']);
                $deleteStmt->execute();   
            }
        }
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

                clearHistory($db, $idPerso);
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
        if(count($result) == 0 && strtolower($name) != 'titres likés' ){
            try{
                $picture = 'playlist/default.png';
                $query = 'INSERT INTO playlist (id, playlist_name, playlist_creation,playlist_picture) VALUES (:id_user, :namePlaylist, :dateAjout,:picture)';
                $statement = $db->prepare($query);
                $statement->bindParam(':id_user', $id_user);
                $statement->bindParam(':namePlaylist', $name);
                $statement->bindParam(':dateAjout', $dateAdd);
                $statement->bindParam(':picture', $picture);
                $statement->execute();
            }catch(Exception $e){
                echo $e->getMessage();
            }
        }else{
            return false;
        }
        return true;
    }

    function dbDeletePlaylist($db,$id_playlist,$id_user){
        $idplaylistLike = getLikePlaylist($db,$id_user);
        if($idplaylistLike != $id_playlist){
            try{
                $query = 'DELETE FROM playlist WHERE id = :id_user AND playlist_id = :id_playlist';
                $statement = $db->prepare($query);
                $statement->bindParam(':id_user', $id_user);
                $statement->bindParam(':id_playlist', $id_playlist);
                $statement->execute();
            }catch(Exception $e){
                echo $e->getMessage();
            }
            return true;
        }
        return false;
    }

    function dbModifyPlaylist($db,$id_playlist,$name,$user_id){
        $idLikedPlaylist = getLikePlaylist($db,$user_id);
        $alreadyExist = alreadyExist($db,$user_id,$name);
        if($idLikedPlaylist != $id_playlist && $alreadyExist == 0){
            try{
                $query = 'UPDATE playlist SET playlist_name = :namePlaylist WHERE playlist_id = :id_playlist';
                $statement = $db->prepare($query);
                $statement->bindParam(':namePlaylist', $name);
                $statement->bindParam(':id_playlist', $id_playlist);
                $statement->execute();
            }catch(Exception $e){
                echo $e->getMessage();
            }
            return true;
        }else{
            return false;
        }
    }

    function alreadyExist($db,$user_id,$name){
        try{
            $query = 'SELECT playlist_id FROM playlist WHERE id = :user_id AND playlist_name = :name';
            $statement = $db->prepare($query);
            $statement->bindParam(':user_id', $user_id);
            $statement->bindParam(':name', $name);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
        if(count($result) == 0){
            return false;
        }else{
            return true;
        }
    }

    function dbModifyAccount($dbConnection, $name,$lastname,$email,$idPerso,$birthdate,$telephone){
        try{
            $query = 'UPDATE utilisateur SET user_firstname = :name, user_lastname = :lastname, user_mail = :email, user_birth = :birthdate, user_telephone = :telephone WHERE id = :idPerso';
            $statement = $dbConnection->prepare($query);
            $statement->bindParam(':name', $name);
            $statement->bindParam(':lastname', $lastname);
            $statement->bindParam(':email', $email);
            $statement->bindParam(':birthdate', $birthdate);
            $statement->bindParam(':telephone', $telephone);
            $statement->bindParam(':idPerso', $idPerso);
            $statement->execute();
        }catch(Exception $e){
            echo $e->getMessage();
        }
        if($statement->rowCount() == 1){
            return true;
        }else{
            return false;
        }
    }

    function dbGetPassword($db,$id){
        try{
            $query = 'SELECT user_password FROM utilisateur WHERE id = :id';
            $statement = $db->prepare($query);
            $statement->bindParam(':id', $id);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
        return $result[0]['user_password'];
    }

    function dbModifyPassword($db,$id,$passwd){
        try{
            $query = 'UPDATE utilisateur SET user_password = :passwd WHERE id = :id';
            $statement = $db->prepare($query);
            $statement->bindParam(':id', $id);
            $statement->bindParam(':passwd', $passwd);
            $statement->execute();
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
        return true;
    }

    function changePath($db, $id,$path){
        try{
            $query = 'UPDATE utilisateur SET user_chemin = :pathPicture WHERE id = :id';
            $statement = $db->prepare($query);
            $statement->bindParam(':id', $id);
            $statement->bindParam(':pathPicture', $path);
            $statement->execute();
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
        return true;
    }

    function dbAddFriendToList($db,$idPerso,$idFriend){
        try{
            $query = 'SELECT * FROM etre_ami WHERE id = :idPerso AND id_user = :idFriend';
            $statement = $db->prepare($query);
            $statement->bindParam(':idPerso', $idPerso);
            $statement->bindParam(':idFriend', $idFriend);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
        if(count($result) == 0){
            try{
                $query = 'INSERT INTO etre_ami (id, id_user, isaccept) VALUES (:idFriend, :idPerso, \'false\')';
                $statement = $db->prepare($query);
                $statement->bindParam(':idFriend', $idFriend);
                $statement->bindParam(':idPerso', $idPerso);
                $statement->execute();
            }
            catch(Exception $e){
                echo $e->getMessage();
            }
            if($statement->rowCount() == 1){
                return [true,$idPerso];
            }else{
                return [false,$idPerso];
            }
        }
        else{
            return [false,$idPerso];
        }
    }
    function dbGetDemandeAmis($db,$id){
        try{
            $query = 'SELECT * from etre_ami ea JOIN utilisateur u ON u.id = ea.id_user  WHERE ea.id = :id AND isaccept = \'false\'';
            $statement = $db->prepare($query);
            $statement->bindParam(':id', $id);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo $e->getMessage();
        }
        return $result;
    }

    function dbGetDemandeAmisEnvoye($db,$id){
        try{
            $query = 'SELECT * from etre_ami ea JOIN utilisateur u ON u.id = ea.id  WHERE ea.id_user = :id AND isaccept = \'false\'';
            $statement = $db->prepare($query);
            $statement->bindParam(':id', $id);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo $e->getMessage();
        }
        return $result;
    }

    function dbGetFriends($db,$id){
        try{
            $query = 'SELECT * from etre_ami ea JOIN utilisateur u ON u.id = ea.id_user  WHERE ea.id = :id AND isaccept = \'true\'';
            $statement = $db->prepare($query);
            $statement->bindParam(':id', $id);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo $e->getMessage();
        }
        return $result;
    }
    

    function dbRechercheFriend($db,$recherche){
        $recherche = '%'.$recherche.'%';
        try{
            $query = 'SELECT user_firstname, user_lastname, id FROM utilisateur WHERE user_firstname LIKE :recherche OR user_lastname LIKE :recherche';
            $statement = $db->prepare($query);
            $statement->bindParam(':recherche', $recherche);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo $e->getMessage();
        }
        return $result;
    }

    function dbAcceptAmi($db,$id,$idFriend){
        try{
            $query = 'UPDATE etre_ami SET isaccept = \'true\' WHERE id = :id AND id_user = :idFriend';
            $statement = $db->prepare($query);
            $statement->bindParam(':id', $id);
            $statement->bindParam(':idFriend', $idFriend);
            $statement->execute();
        }catch(Exception $e){
            echo $e->getMessage();
        }
        try{
            $query = 'INSERT INTO etre_ami (id, id_user, isaccept) VALUES (:idFriend, :id, \'true\')';
            $statement = $db->prepare($query);
            $statement->bindParam(':idFriend', $idFriend);
            $statement->bindParam(':id', $id);
            $statement->execute();
        }catch(Exception $e){
            echo $e->getMessage();
        }
        return [true,$id];
    }

    function dbRefuseAmi($db,$id,$idFriend){
        try{
            $query = 'DELETE FROM etre_ami WHERE id = :id AND id_user = :idFriend';
            $statement = $db->prepare($query);
            $statement->bindParam(':id', $id);
            $statement->bindParam(':idFriend', $idFriend);
            $statement->execute();
        }catch(Exception $e){
            echo $e->getMessage();
        }
        return [true,$id];
    }

    function dbAnnulerDemandeAmi($db,$idPerso,$id_Friend){
        try{
            $query = 'DELETE FROM etre_ami WHERE id = :id_Friend AND id_user = :idPerso';
            $statement = $db->prepare($query);
            $statement->bindParam(':idPerso', $idPerso);
            $statement->bindParam(':id_Friend', $id_Friend);
            $statement->execute();
        }catch(Exception $e){
            echo $e->getMessage();
        }
        return [true,$idPerso];
    }

    function dbDeleteAmiBothSide($db,$idPerso,$id_friend){
        try{
            $query = 'DELETE FROM etre_ami WHERE id = :id_friend AND id_user = :idPerso';
            $statement = $db->prepare($query);
            $statement->bindParam(':idPerso', $idPerso);
            $statement->bindParam(':id_friend', $id_friend);
            $statement->execute();
        }catch(Exception $e){
            echo $e->getMessage();
        }
        try{
            $query = 'DELETE FROM etre_ami WHERE id = :idPerso AND id_user = :id_friend';
            $statement = $db->prepare($query);
            $statement->bindParam(':id_friend', $id_friend);
            $statement->bindParam(':idPerso', $idPerso);
            $statement->execute();
        }catch(Exception $e){
            echo $e->getMessage();
        }
        return [true,$idPerso];
    }

    function dbGetAccountFriend($db,$idFriend){
        try{
            $query = 'SELECT DISTINCT * FROM utilisateur u JOIN playlist p ON u.id = p.id WHERE u.id = :idFriend';
            $statement = $db->prepare($query);
            $statement->bindParam(':idFriend', $idFriend);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo $e->getMessage();
        }
        return $result;
    }

    function dbGetPlaylistFriend($db,$idPlaylist){
        try{
            $query = 'SELECT DISTINCT * FROM playlist p JOIN utilisateur ul ON ul.id = p.id JOIN music_contenu mc ON p.playlist_id = mc.playlist_id JOIN music m ON m.music_id = mc.music_id JOIN album a ON m.id_album = a.id_album JOIN artiste ar ON ar.artiste_id = a.artiste_id WHERE p.playlist_id = :idPlaylist';
            $statement = $db->prepare($query);
            $statement->bindParam(':idPlaylist', $idPlaylist);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo $e->getMessage();
        }
        if(count($result) ==0){
            $query = 'SELECT DISTINCT * FROM playlist p JOIN utilisateur u ON u.id = p.id WHERE p.playlist_id = :idPlaylist';
            $statement = $db->prepare($query);
            $statement->bindParam(':idPlaylist', $idPlaylist);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        return $result;
    }

    function dbGetMessageBetweenFriend($db,$idPerso,$id_friend){
        try{
            $query = 'SELECT * FROM discussion m JOIN utilisateur u ON u.id = m.id_user WHERE (m.id_user = :idPerso AND m.id = :id_friend) OR (m.id_user = :id_friend AND m.id = :idPerso) ORDER BY m.date_discussion ASC';
            $statement = $db->prepare($query);
            $statement->bindParam(':idPerso', $idPerso);
            $statement->bindParam(':id_friend', $id_friend);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo $e->getMessage();
        }
        return $result;
    }

    function dbGetChat($db,$idPerso,$id_friend){
        try{
            $query = 'SELECT * FROM discussion m JOIN utilisateur u ON u.id = m.id_user WHERE (m.id_user = :idPerso AND m.id = :id_friend) OR (m.id_user = :id_friend AND m.id = :idPerso) ORDER BY m.date_discussion ASC';
            $statement = $db->prepare($query);
            $statement->bindParam(':idPerso', $idPerso);
            $statement->bindParam(':id_friend', $id_friend);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo $e->getMessage();
        }
        if(count($result) ==0){
            $query = 'SELECT * FROM utilisateur WHERE id = :id_friend';
            $statement = $db->prepare($query);
            $statement->bindParam(':id_friend', $id_friend);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        return $result;
    }

    function dbAddMessage($db,$id_perso,$id_friend,$message){
        $date = date("Y-m-d H:i:s");
        try{
            $query = 'INSERT INTO discussion (id, id_user, message_text, date_discussion) VALUES (:id_friend, :id_perso, :messageText, :dateAjd)';
            $statement = $db->prepare($query);
            $statement->bindParam(':id_friend', $id_friend);
            $statement->bindParam(':id_perso', $id_perso);
            $statement->bindParam(':messageText', $message);
            $statement->bindParam(':dateAjd', $date);
            $statement->execute();
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
        return true;
    }

    ?>