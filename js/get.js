import {ajaxRequest} from "./ajax.js";
import {displayLastEcoute, displayPlaylist, displayOneArtistResponse,displayOneAlbumResponse} from "./display.js";

export function getLastEcoute(){
    let id = document.getElementById('id_perso').value;
    console.log()
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





// displayOneArtist(1);

