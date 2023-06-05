import {ajaxRequest} from "./ajax.js";
import {displayAccountFriend, displayRechercheArtist, displayRechercheAlbum ,displayLastEcoute, displayPlaylist, displayOneArtistResponse,displayOneAlbumResponse, displayRechercheMusic} from "./display.js";

//FONCTION DE RECUPERATION DES DONNEES --> LAST ECOUTE
export function getLastEcoute(){
    let id = document.getElementById('id_perso').value;
    ajaxRequest('GET','php/request.php?request=last_ecoute&id_perso='+id,displayLastEcoute,null);
}

//FONCTION DE RECUPERATION DES DONNEES --> PLAYLIST
export function getPlaylistAccueil(){
    let id = document.getElementById('id_perso').value;
    ajaxRequest('GET','php/request.php?request=playlist&id_perso='+id,displayPlaylist,null);
}

//FONCTION DE RECUPERATION DES DONNEES --> ARTISTE
export function getOneArtist(id_artist){
    ajaxRequest('GET','php/request.php?request=getOneArtist&idArtist=' + id_artist,displayOneArtistResponse);
}

//FONCTION DE RECUPERATION DES DONNEES --> ALBUM
export function getOneAlbum(id_album){
    let id_user = document.getElementById('id_perso').value;
    ajaxRequest('GET','php/request.php?request=getOneAlbum&idAlbum=' + id_album+'&idPerso='+id_user,displayOneAlbumResponse);
}

//FONCTION DE RECUPERATION DES DONNEES --> CURRENT USER
export function getCurrentUser(){
    let id = document.getElementById('id_perso').value;
    ajaxRequest('GET','php/request.php?request=getCurrentUser&idPerso='+id,displayCurrentUser);
}

//FONCTION DE RECUPERATION DES DONNEES --> RECHERCHE DE MUSIQUE
export function getSearchMusic(recherche){
    let id_user = document.getElementById('id_perso').value;
    ajaxRequest('GET','php/request.php?request=searchMusic&recherche='+recherche+'&idPerso='+id_user,displayRechercheMusic);
}

//FONCTION DE RECUPERATION DES DONNEES --> RECHERCHE D'ALBUM
export function getSearchAlbum(recherche){
    ajaxRequest('GET','php/request.php?request=searchAlbum&recherche='+recherche,displayRechercheAlbum);
}

//FONCTION DE RECUPERATION DES DONNEES --> RECHERCHE D'ARTISTE
export function getSearchArtist(recherche){
    ajaxRequest('GET','php/request.php?request=searchArtist&recherche='+recherche,displayRechercheArtist);
}

//FONCTION DE RECUPERATION DES DONNEES --> AMIS
export function getAccountFriend(id){
    ajaxRequest('GET','php/request.php?request=getAccountFriend&idPerso='+id,displayAccountFriend);
}

