<?php

    // Database connection
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    require_once("constants.php");

    //FONCTION POUR CREER LA BASE DE DONNEE
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

    //FONCTION POUR SAVOIR SI L'UTILISATEUR EXISTE
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
    
    //FONCTION POUR RECUPERER LE MOT DE PASSE ENCRYPTÉ
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

    //FONCTION POUR UPDATE UN COMPTE
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

    //FONCTION POUR UPDATE UN PASSWORD
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

    //FONCTION POUR RECUPERER UN UTILISATEUR
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

    //FONCTION POUR AJOUTER UN USER
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

    //FONCTION POUR RECUPERER LES 10 DERNIERES ECOUTES
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
                LIMIT 20';
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
    
    //FONCTION POUR RECUPERER LES INFOS D'UNE MUSIQUE
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

    //FONCTION POUR RECUPERER UNE PLAYLIST
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

    //FONCTION QUI INITIALISE LA PLAYLIST LIKE
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

    //FONCTION QUI RECUPERE LA PLAYLIST LIKE D'UN USER
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

    //FONCTION QUI PERMET D'AJOUTER UNE MUSIQUE A LA PLAYLIST LIKE
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

    //FONCTION QUI PERMET DE SAVOIR SI UNE MUSIQUE EST DANS LA PLAYLIST LIKE
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

    //FONCTION QUI RECUPERE LES INFORMATIONS D'UNE PLAYLIST EN AJOUTANT SI LA MUSIQUE EST DANS LA PLAYLIST LIKE
    function dbGetOnePlaylist($dbConnection,$idPlaylist,$idPerso){
        $id_titre_like = getLikePlaylist($dbConnection,$idPerso);

        try{
            $query = 'SELECT * FROM music_contenu WHERE playlist_id = :idPlaylist';
            $statement = $dbConnection->prepare($query);
            $statement->bindParam(':idPlaylist', $idPlaylist);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo $e->getMessage();
        }
        if(count($result) > 0){
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
        }
        else{
            try{
                $query = 'SELECT * FROM playlist a JOIN utilisateur u ON u.id = a.id WHERE a.playlist_id = :idPlaylist AND a.id = :idPerso';
                $statement = $dbConnection->prepare($query);
                $statement->bindParam(':idPlaylist', $idPlaylist);
                $statement->bindParam(':idPerso', $idPerso);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            }catch(Exception $e){
                echo $e->getMessage();
            }
        }
        return $result;
    }

    //FONCTION QUI RECUPERE UN ARTISTE
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

    //FONCTION QUI RECUPERE LE TOP 3 DES MUSIQUES D'UN ARTISTE
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

    //FONCTION QUI RECUPERER UN ALBUM
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

    //FONCTION QUI RECUPERE LES INFORMATIONS D'UNE MUSIC ET DES PLAYLISTS
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

    //FONCTION QUI AJOUTE UNE MUSIQUE A UNE PLAYLIST
    function dbAddToPlaylist($db,$id_music,$id_playlist,$id_perso){
        $isInPlaylist = isInPlaylist($db,$id_music,$id_playlist);
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

    //FONCTION QUI DIT SI UNE MUSIQUE EST DANS UNE PLAYLIST
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
    
    //FNCTION QUI DELTE UNE MUSIQUE DANS UNE PLAYLIST
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

    //FONCTION QUI RECUPERER UNE MUSIQUE
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

    //FONCTION QUI CLEAR HISTORIQUE AU BOUT DE 10 MUSIQUES
    function clearHistory($db, $personId) {
        // Requête SQL pour compter le nombre de musiques pour la personne donnée
        $countSql = "SELECT COUNT(*) as total FROM historique WHERE id = :personId";
        $countStmt = $db->prepare($countSql);
        $countStmt->bindParam(':personId', $personId);
        $countStmt->execute();
        $countResult = $countStmt->fetch(PDO::FETCH_ASSOC);
    
        $totalMusics = intval($countResult['total']);
    
        if ($totalMusics > 20) {
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
    
    //FONCTION QUI UPDATE L'HISTORIQUE
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

    //FONCTION QUI RECUPERE UN USER
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

    //FONCTION QUI RECUPERE LA PHOOT DE PROFIL
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

    //FONCTION QUI VA ENLEVER LES CHIFFRES D'UNE CHAINE DE CARACTERE POUR VOIR SI C'EST UN NOMBRE
    function is_numericText($text){
        $text = str_replace('0','',$text);
        $text = str_replace('1','',$text);
        $text = str_replace('2','',$text);
        $text = str_replace('3','',$text);
        $text = str_replace('4','',$text);
        $text = str_replace('5','',$text);
        $text = str_replace('6','',$text);
        $text = str_replace('7','',$text);
        $text = str_replace('8','',$text);
        $text = str_replace('9','',$text);
        if($text == ''){
            return true;
        }else{
            return false;
        }
    }

    //FONCTION DE RECHERCHE DE MUSIQUE
    function dbRechercheMusic($db, $recherche,$idPerso){
        $id_playlist = getLikePlaylist($db,$idPerso);
        if (strlen($recherche) == 4 && is_numericText($recherche)) {
            // $recherche = $recherche . '%';
            $query = 'SELECT DISTINCT music.*, album.*, artiste.*, 
                CASE WHEN music.music_id IN (
                    SELECT music_id FROM music_contenu WHERE playlist_id = :id_playlistlike
                ) THEN TRUE ELSE FALSE END AS isliked
                FROM music
                JOIN album ON music.id_album = album.id_album
                JOIN artiste ON album.artiste_id = artiste.artiste_id
                WHERE EXTRACT(YEAR FROM album.album_creation) = :recherche';
            $statement = $db->prepare($query);
            $statement->bindParam(':recherche', $recherche);
            $statement->bindParam(':id_playlistlike', $id_playlist);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }else{
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
    }

    //FONCTION DE RECHERCHE D'ALBUM
    function dbRechercheAlbum($db,$recherche){
        if (strlen($recherche) == 4 && is_numericText($recherche)) {
            // $recherche = $recherche . '%';
            $query = 'SELECT DISTINCT album.*, artiste.*
                FROM album
                JOIN artiste ON album.artiste_id = artiste.artiste_id
                WHERE EXTRACT(YEAR FROM album.album_creation) = :recherche';
            $statement = $db->prepare($query);
            $statement->bindParam(':recherche', $recherche);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }else{
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
    }

    //FONCTION DE RECHERCHE D'ARTISTE
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

    //FONCTION D'AJOUT D'UNE PLAYLIST
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

    //FONCTION DE DELETE D'UNE PLAYLIST
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

    //FONCTION POUR MODIFIER UNE PLAYLIST
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

    //FONCTION QUI DIT SI UNE PLAYLIST EXISTE DEJA AVEC SON NOM
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

    //FONCTION QIU MODIFIE UN COMPTE
    function dbModifyAccount($dbConnection, $name,$lastname,$email,$idPerso,$birthdate,$telephone){
        try{
            $query = 'SELECT * FROM utilisateur WHERE user_mail = :email AND id != :idPerso';
            $statement = $dbConnection->prepare($query);
            $statement->bindParam(':email', $email);
            $statement->bindParam(':idPerso', $idPerso);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
        if(count($result) != 0){
            return false;
        }
        else{
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
    }

    //FONCTION QUI RECUPERE LE PASSWORD
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

    //FONCTION QUI MODIFIE LE PASSWORD
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

    //FONCTION QUI MODIFIE LE PATH DE LA PHOTO DE PROFIL
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

    function changePathPlaylist($db,$idPlaylistlist,$chemin){
        try{
            $query = 'UPDATE playlist SET playlist_picture = :chemin WHERE playlist_id = :idPlaylist';
            $statement = $db->prepare($query);
            $statement->bindParam(':idPlaylist', $idPlaylistlist);
            $statement->bindParam(':chemin', $chemin);
            $statement->execute();
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }

    //FONCTION D'AJOUT D'UN AMI
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

    //FONCTION QUI RECUPERE LES DEMANDES AMIS
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

    //FONCTION QUI RECUPERE LES DEMANDES AMIS ENVOYEES
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

    //FONCTION QUI RECUPERE LES AMIS
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
    
    //FONCTION QUI CHERCHE UN AMI
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

    //FONCTION POUR ACCEPTER UN AMI
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

    //FONCTION POUR REFUSER UN AMI
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

    //FONCTION POUR ANNULER UNE DEMANDE D'AMI
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

    //FONCTION POUR SUPPRIMER UN AMI
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

    //FONCTION POUR RECUPERER LE COMPTE D'UN AMI
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

    //FONCTION POUR RECUPERER LES PLAYLIST D'UN AMI
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

    //FONCTION QUI RECUPERE LES MESSAGES ENTRE DEUX AMIS
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

    //FONCTION QUI RECUPERE LES CHATS
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

    //FONCTION QUI AJOUTE UN MESSAGE
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

    //FONCTION QUI RENVOI LA DERNIERE MUSIQUE : 
    function dbGetLastMusic($db,$idPerso){
        try{
            $query = 'SELECT * from historique h JOIN music m ON h.music_id = m.music_id WHERE h.id = :id ORDER BY h.last_ecoute DESC LIMIT 2';
            $statement = $db->prepare($query);
            $statement->bindParam(':id', $idPerso);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo $e->getMessage();
        }
        if(count($result) == 0 || count($result) == 1){
            return false;
        }else{
            try{
                $query = 'SELECT * from historique h JOIN music m ON h.music_id = m.music_id WHERE h.id = :id ORDER BY h.last_ecoute DESC LIMIT 2';
                $statement = $db->prepare($query);
                $statement->bindParam(':id', $idPerso);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            }catch(Exception $e){
                echo $e->getMessage();
            }
            deleteMusicHistory($db,$idPerso,$result[0]['music_id']);
            return $result[1];
        }
    }

    //FONCTION QUI SUPPRIME UNE MUSIQUE DE L'HISTORIQUE
    function deleteMusicHistory($db,$idPerso,$result){
        try{
            $query = 'DELETE FROM historique WHERE id = :id AND music_id = :music_id';
            $statement = $db->prepare($query);
            $statement->bindParam(':id', $idPerso);
            $statement->bindParam(':music_id', $result);
            $statement->execute();
        }catch(Exception $e){
            echo $e->getMessage();
        }
        return true;
    }

    //FONCTION QUI RECUPERE LA MUSIQUE SUIVANTE
    function dbGetNextMusic($db,$idPerso){
        try{
            $query = 'SELECT * from liste_attente la JOIN music m ON la.music_id = m.music_id WHERE la.id = :id ORDER BY la.date_ajout DESC LIMIT 1';
            $statement = $db->prepare($query);
            $statement->bindParam(':id', $idPerso);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo $e->getMessage();
        }
        if(count($result) == 0){
            $query = 'SELECT * FROM music ORDER BY RANDOM() LIMIT 1';
            $statement = $db->prepare($query);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $result[0];
        }else{
            try{
                $query = 'SELECT * from liste_attente la JOIN music m ON la.music_id = m.music_id WHERE la.id = :id ORDER BY la.date_ajout DESC LIMIT 1';
                $statement = $db->prepare($query);
                $statement->bindParam(':id', $idPerso);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            }catch(Exception $e){
                echo $e->getMessage();
            }
            deleteMusicListeAttente($db,$idPerso,$result[0]['music_id']);
            return $result[0];
        }
    }

        //FONCTION QUI SUPPRIME DE LA LISTE D'ATTENTE
        function deleteMusicListeAttente($db,$idPerso,$result){
            try{
                $query = 'DELETE FROM liste_attente WHERE id = :id AND music_id = :music_id';
                $statement = $db->prepare($query);
                $statement->bindParam(':id', $idPerso);
                $statement->bindParam(':music_id', $result);
                $statement->execute();
            }catch(Exception $e){
                echo $e->getMessage();
            }
            return true;
        }

     //FONCTION QUI AJOUTE A LA FILE D'ATTENTE UNE MUSIQUE
     function dbAddMusicToFile($db,$idPerso,$idMusic){
        $date = date("Y-m-d H:i:s");
        try{
            $query = 'INSERT INTO liste_attente (id, music_id, date_ajout) VALUES (:id, :music_id, :dateAjd)';
            $statement = $db->prepare($query);
            $statement->bindParam(':id', $idPerso);
            $statement->bindParam(':music_id', $idMusic);
            $statement->bindParam(':dateAjd', $date);
            $statement->execute();
        }catch(Exception $e){
            echo $e->getMessage();
        }
        return true;
    }

    //FONCTION POUR ECOUTER UN ALBUM :
    function dbPlayAlbum($db,$idPerso,$idalbum){
        try{
            $query = 'SELECT * FROM music WHERE id_album = :idalbum';
            $statement = $db->prepare($query);
            $statement->bindParam(':idalbum', $idalbum);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo $e->getMessage();
        }
        clearFileAttente($db,$idPerso);
        foreach($result as $music){
            dbAddMusicToFile($db,$idPerso,$music['music_id']);
        }
        return $result[0]['music_id'];
    }

    //FONCTION QUI LIT UNE PLAYLIST
    function dbPlayPlaylist($db,$idPerso,$playlist_id){
        try{
            $query = 'SELECT * FROM music_contenu JOIN music ON music.music_id = music_contenu.music_id WHERE playlist_id = :playlist_id';
            $statement = $db->prepare($query);
            $statement->bindParam(':playlist_id', $playlist_id);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo $e->getMessage();
        }
        clearFileAttente($db,$idPerso);
        foreach($result as $music){
            dbAddMusicToFile($db,$idPerso,$music['music_id']);
        }
        return $result[0]['music_id'];
    }

    //FONCTION QUI CLEAR LA FILE D'ATTENTE : 
    function clearFileAttente($db,$idPerso){
        try{
            $query = 'DELETE FROM liste_attente WHERE id = :id';
            $statement = $db->prepare($query);
            $statement->bindParam(':id', $idPerso);
            $statement->execute();
        }catch(Exception $e){
            echo $e->getMessage();
        }
        return true;
    }

    //FONCTION QUI RENVOI 10 MUSIQUES ALEATOIRE
    function dbGetTenMusic($db){
        try{
            $query = 'SELECT * FROM music m JOIN album a ON a.id_album = m.id_album JOIN artiste ar ON ar.artiste_id = a.artiste_id ORDER BY RANDOM() LIMIT 10';
            $statement = $db->prepare($query);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
        return $result;
    }

    ?>