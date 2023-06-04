import {ajaxRequest} from "./ajax.js";
import {displayRechercheArtist, displayRechercheAlbum ,displayLastEcoute, displayPlaylist, displayOneArtistResponse,displayOneAlbumResponse, displayCurrentUser, displayRechercheMusic} from "./display.js";

export function getLastEcoute(){
    let id = document.getElementById('id_perso').value;
    ajaxRequest('GET','php/request.php?request=last_ecoute&id_perso='+id,displayLastEcoute,null);
}

export function getPlaylistAccueil(){
    let id = document.getElementById('id_perso').value;
    ajaxRequest('GET','php/request.php?request=playlist&id_perso='+id,displayPlaylist,null);
}
export function getOneArtist(id_artist){
    ajaxRequest('GET','php/request.php?request=getOneArtist&idArtist=' + id_artist,displayOneArtistResponse);
}

export function getOneAlbum(id_album){
    let id_user = document.getElementById('id_perso').value;
    ajaxRequest('GET','php/request.php?request=getOneAlbum&idAlbum=' + id_album+'&idPerso='+id_user,displayOneAlbumResponse);
}

export function getCurrentUser(){
    let id = document.getElementById('id_perso').value;
    ajaxRequest('GET','php/request.php?request=getCurrentUser&idPerso='+id,displayCurrentUser);
}

export function getSearchMusic(recherche){
    let id_user = document.getElementById('id_perso').value;
    ajaxRequest('GET','php/request.php?request=searchMusic&recherche='+recherche+'&idPerso='+id_user,displayRechercheMusic);
}

export function getSearchAlbum(recherche){
    ajaxRequest('GET','php/request.php?request=searchAlbum&recherche='+recherche,displayRechercheAlbum);
}
export function getSearchArtist(recherche){
    ajaxRequest('GET','php/request.php?request=searchArtist&recherche='+recherche,displayRechercheArtist);
}





// displayOneArtist(1);

