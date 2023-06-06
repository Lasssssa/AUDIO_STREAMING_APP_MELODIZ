
DROP TABLE IF EXISTS utilisateur CASCADE;
DROP TABLE IF EXISTS artiste CASCADE;
DROP TABLE IF EXISTS album CASCADE;
DROP TABLE IF EXISTS music CASCADE;
DROP TABLE IF EXISTS playlist CASCADE;
DROP TABLE IF EXISTS historique CASCADE;
DROP TABLE IF EXISTS music_cree CASCADE;
DROP TABLE IF EXISTS music_contenu CASCADE;
DROP TABLE IF EXISTS etre_ami CASCADE;
DROP TABLE IF EXISTS discussion CASCADE;
DROP TABLE IF EXISTS liste_attente CASCADE;

------------------------------------------------------------
--        Script Postgre 
------------------------------------------------------------



------------------------------------------------------------
-- Table: utilisateur
------------------------------------------------------------
CREATE TABLE public.utilisateur(
	id               SERIAL NOT NULL ,
	user_firstname   VARCHAR (50) NOT NULL ,
	user_lastname    VARCHAR (50) NOT NULL ,
	user_mail        VARCHAR (100) NOT NULL ,
	user_password    VARCHAR (150) NOT NULL ,
	user_birth       DATE  NOT NULL ,
	creation_date    DATE  NOT NULL ,
	user_telephone   VARCHAR (50) NOT NULL ,
	user_bio         VARCHAR (250)  ,
	user_chemin      VARCHAR (100) NOT NULL ,
	CONSTRAINT utilisateur_PK PRIMARY KEY (id)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: artiste
------------------------------------------------------------
CREATE TABLE public.artiste(
	artiste_id         SERIAL NOT NULL ,
	artiste_name       VARCHAR (50) NOT NULL ,
	artiste_lastname   VARCHAR (50),
	artiste_type       VARCHAR (20) NOT NULL  ,
	artiste_bio        VARCHAR (250)  ,
	artiste_chemin     VARCHAR (100) NOT NULL ,
	CONSTRAINT artiste_PK PRIMARY KEY (artiste_id)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: album
------------------------------------------------------------
CREATE TABLE public.album(
	id_album         SERIAL NOT NULL ,
	album_creation   DATE  NOT NULL ,
	album_title      CHAR (50)  NOT NULL ,
	album_style      VARCHAR (50) NOT NULL ,
	artiste_id       INT  NOT NULL  ,
	album_chemin      VARCHAR (100) NOT NULL ,
	CONSTRAINT album_PK PRIMARY KEY (id_album)

	,CONSTRAINT album_artiste_FK FOREIGN KEY (artiste_id) REFERENCES public.artiste(artiste_id)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: music
------------------------------------------------------------
CREATE TABLE public.music(
	music_id         SERIAL NOT NULL ,
	music_title      VARCHAR (50) NOT NULL ,
	music_duration   TIME  NOT NULL ,
	id_album         INT  NOT NULL  ,
	music_play_chemin  VARCHAR (150) NOT NULL ,
	CONSTRAINT music_PK PRIMARY KEY (music_id)

	,CONSTRAINT music_album_FK FOREIGN KEY (id_album) REFERENCES public.album(id_album)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: playlist
------------------------------------------------------------
CREATE TABLE public.playlist(
	playlist_id         SERIAL NOT NULL ,
	playlist_name       VARCHAR (50) NOT NULL ,
	playlist_creation   DATE  NOT NULL ,
	id                  INT  NOT NULL  ,
	playlist_picture    VARCHAR (100) NOT NULL ,
	CONSTRAINT playlist_PK PRIMARY KEY (playlist_id)

	,CONSTRAINT playlist_utilisateur_FK FOREIGN KEY (id) REFERENCES public.utilisateur(id)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: historique
------------------------------------------------------------
CREATE TABLE public.historique(
	music_id      INT  NOT NULL ,
	id            INT  NOT NULL ,
	last_ecoute   TIMESTAMP  NOT NULL ,
	CONSTRAINT historique_PK PRIMARY KEY (music_id,id)

	,CONSTRAINT historique_music_FK FOREIGN KEY (music_id) REFERENCES public.music(music_id)
	,CONSTRAINT historique_utilisateur0_FK FOREIGN KEY (id) REFERENCES public.utilisateur(id)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: music_cree
------------------------------------------------------------
CREATE TABLE public.music_cree(
	music_id     INT  NOT NULL ,
	artiste_id   INT  NOT NULL  ,
	CONSTRAINT music_cree_PK PRIMARY KEY (music_id,artiste_id)

	,CONSTRAINT music_cree_music_FK FOREIGN KEY (music_id) REFERENCES public.music(music_id)
	,CONSTRAINT music_cree_artiste0_FK FOREIGN KEY (artiste_id) REFERENCES public.artiste(artiste_id)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: music_contenu
------------------------------------------------------------
CREATE TABLE public.music_contenu(
	music_id      INT  NOT NULL ,
	playlist_id   INT  NOT NULL ,
	date_ajout    DATE  NOT NULL  ,
	CONSTRAINT music_contenu_PK PRIMARY KEY (music_id,playlist_id)

	,CONSTRAINT music_contenu_music_FK FOREIGN KEY (music_id) REFERENCES public.music(music_id)
	,CONSTRAINT music_contenu_playlist0_FK FOREIGN KEY (playlist_id) REFERENCES public.playlist(playlist_id) ON DELETE CASCADE
)WITHOUT OIDS;

------------------------------------------------------------
-- Table: etre_ami
------------------------------------------------------------
CREATE TABLE public.etre_ami(
	id        INT  NOT NULL ,
	id_User   INT  NOT NULL  ,
	isAccept  BOOLEAN  NOT NULL ,
	CONSTRAINT etre_ami_PK PRIMARY KEY (id,id_User)

	,CONSTRAINT etre_ami_User_FK FOREIGN KEY (id) REFERENCES public.utilisateur(id)
	,CONSTRAINT etre_ami_User0_FK FOREIGN KEY (id_User) REFERENCES public.utilisateur(id)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: discussion
------------------------------------------------------------
CREATE TABLE public.discussion(
	date_discussion   TIMESTAMP  NOT NULL ,
	message_text      VARCHAR (250) NOT NULL ,
	id                INT   ,
	id_User           INT    ,
	CONSTRAINT discussion_PK PRIMARY KEY (date_discussion)

	,CONSTRAINT discussion_User_FK FOREIGN KEY (id) REFERENCES public.utilisateur(id)
	,CONSTRAINT discussion_User0_FK FOREIGN KEY (id_User) REFERENCES public.utilisateur(id)
)WITHOUT OIDS;

------------------------------------------------------------
-- Table: liste_attente
------------------------------------------------------------
CREATE TABLE public.liste_attente(
	id           INT  NOT NULL ,
	music_id     INT  NOT NULL ,
	date_ajout   DATE  NOT NULL  ,
	CONSTRAINT liste_attente_PK PRIMARY KEY (id,music_id)

	,CONSTRAINT liste_attente_User_FK FOREIGN KEY (id) REFERENCES public.utilisateur(id)
	,CONSTRAINT liste_attente_Music0_FK FOREIGN KEY (music_id) REFERENCES public.Music(music_id)
)WITHOUT OIDS;
