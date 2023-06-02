DELETE FROM utilisateur;
DELETE FROM artiste;
DELETE FROM album;
DELETE FROM music;
DELETE FROM playlist;
DELETE FROM historique;
DELETE FROM music_cree;
DELETE FROM music_contenu;

INSERT INTO utilisateur(user_firstname,user_lastname,user_mail,user_password,user_birth,creation_date,user_telephone) 
VALUES ('Théo','Porodo','theo@gmail.com','$2y$10$khpSwpAirYuBAyqICSaJ5eWM81ag9mWhFXNdTJiCNWaf7J/a5Y8my', '1998-12-12', '2018-12-12', '0606060606');

INSERT INTO artiste(artiste_name, artiste_lastname, artiste_type) VALUES
('Gambi',null,'Chanteur'),
('Daniel','Balavoine','Chanteur'),
('Michel','Sardou','Chanteur'),
('PNL',null,'Groupe'),
('Jul',null,'Chanteur');


INSERT INTO album(album_creation,album_title,album_style,artiste_id) VALUES 
('2019-12-12','La vie est belle','Rap',1),
('1980-12-12','Sauver l amour','Chanson',2),
('1980-12-12','Les lacs du Connemara','Chanson',3),
('2019-12-12','Deux frères','Rap',4),
('2019-12-12','Rien 100 rien','Rap',5);

INSERT INTO music(id_deezer,music_title,music_duration,id_album) VALUES 
(1,'La vie',2.50,1),
(2,'La vie est belle',2.50,1),
(3,'Popopop',2.50,1),
(4,'Hé oh',2.50,1),
(5,'Dans l espace',2.50,1),
(6,'Sauver l amour',2.50,2),
(7,'Vivre ou survivre',2.50,2),
(8,'L Aziza',2.50,2),
(9,'Mon fils ma bataille',2.50,2),
(10,'Dieu que c est beau',2.50,2),
(11,'Les lacs du Connemara',2.50,3),
(12,'Je vais t aimer',2.50,3),
(13,'La maladie d amour',2.50,3),
(14,'En chantant',2.50,3),
(15,'Je vole',2.50,3),
(16,'Au DD',2.50,4),
(17,'91 s',2.50,4),
(18,'Celsius',2.50,4),
(19,'Déconnecté',2.50,4),
(20,'Shenmue',2.50,4),
(21,'Rien 100 rien',2.50,5),
(22,'C est pas des lol',2.50,5),
(23,'C est pas des lol',2.50,5),
(24,'C est pas des lol',2.50,5),
(25,'C est pas des lol',2.50,5);

INSERT INTO music_cree(music_id,artiste_id) VALUES
(1,1),
(2,1),
(3,1),
(4,1),
(5,1),
(6,2),
(7,2),
(8,2),
(9,2),
(10,2),
(11,3),
(12,3),
(13,3),
(14,3),
(15,3),
(16,4),
(17,4),
(18,4),
(19,4),
(20,4),
(21,5),
(22,5),
(23,5),
(24,5),
(25,5);

INSERT INTO playlist (playlist_name, playlist_creation,id,havePicture) VALUES
('Titres likés','2019-12-12',1,'true'),
('Playlist 1','2019-12-12',1,'false'),
('Playlist 2','2019-12-12',1,'false'),
('Playlist 3','2019-12-12',1,'false'),
('Playlist 4','2019-12-12',1,'false'),
('Playlist 5','2019-12-12',1,'false');

INSERT INTO music_contenu (music_id,playlist_id,date_ajout) VALUES
(1,1,'2019-12-12'),
(2,1,'2019-12-12'),
(3,1,'2019-12-12'),
(4,1,'2019-12-12'),
(5,1,'2019-12-12'),
(6,2,'2019-12-12'),
(7,2,'2019-12-12'),
(8,2,'2019-12-12'),
(9,2,'2019-12-12'),
(10,2,'2019-12-12'),
(11,3,'2019-12-12'),
(12,3,'2019-12-12'),
(13,3,'2019-12-12'),
(14,3,'2019-12-12'),
(15,3,'2019-12-12'),
(16,4,'2019-12-12'),
(17,4,'2019-12-12'),
(18,4,'2019-12-12'),
(19,4,'2019-12-12'),
(20,4,'2019-12-12'),
(21,5,'2019-12-12'),
(22,5,'2019-12-12'),
(23,5,'2019-12-12'),
(24,5,'2019-12-12'),
(25,5,'2019-12-12');

INSERT INTO historique (music_id,id,last_ecoute) VALUES
(1,1,'2019-12-12'),
(2,1,'2019-12-12'),
(3,1,'2019-12-12'),
(4,1,'2019-12-12'),
(5,1,'2019-12-12'),
(6,1,'2019-12-12'),
(7,1,'2019-12-12'),
(8,1,'2019-12-12'),
(9,1,'2019-12-12'),
(10,1,'2019-12-12');

