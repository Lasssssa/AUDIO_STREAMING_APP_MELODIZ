import {ajaxRequest} from "./ajax.js";
import { displayOnePlaylist } from "./display.js";
import { getLastEcoute } from "./get.js";
import {nextMusicResponse} from "./accueil.js";

//FONCTION QUI PERMET DE LIKE UNE MUSIQUE
export function likeMusic(idMusic,page) {
    let id_user = document.getElementById('id_perso').value;
    let data = 'request=likeMusic&idPerso='+id_user+'&idMusic='+idMusic;
    if(page == 'accueil'){
        ajaxRequest('POST','php/request.php',likeMusicAccueil,data);
    }
    if(page == 'album'){
        ajaxRequest('POST','php/request.php',likeMusicAlbum,data);
    }
    if(page == 'playlist'){
        ajaxRequest('POST','php/request.php',likeMusicPlaylist,data);
    }
    if(page == 'artiste'){
        ajaxRequest('POST','php/request.php',likeMusicArtist,data);
    }
    if(page == 'recherche'){
        ajaxRequest('POST','php/request.php',likeMusicRecherche,data);
    }
    if(page == 'ami'){
        ajaxRequest('POST','php/request.php',likeMusicFriend,data);
    }
}

//FONCTION QUI MODIFIE LE LIKE SUR LA PAGE FRIEND
function likeMusicFriend(data) {
    let buttonModif = document.getElementById('likeAmi_'+data[0]);
    if(data[1] == 1){
        buttonModif.innerHTML = '<i class="material-icons">favorite</i>';
    }else{
        buttonModif.innerHTML = '<i class="material-icons">favorite_border</i>';
    }
}

//FONCTION QUI MODIFIE LE LIKE SUR LA PAGE RECHERCHE
function likeMusicRecherche(data) {
    let buttonModif = document.getElementById('likeRecherche_'+data[0]);
    if(data[1] == 1){
        buttonModif.innerHTML = '<i class="material-icons">favorite</i>';
    }else{
        buttonModif.innerHTML = '<i class="material-icons">favorite_border</i>';
    }
}

//FONCTION QUI MODIFIE LE LIKE SUR LA PAGE ARTISTE
function likeMusicArtist(data) {
    let buttonModif = document.getElementById('likeArtist_'+data[0]);
    if(data[1] == 1){
        buttonModif.innerHTML = '<i class="material-icons">favorite</i>';
    }else{
        buttonModif.innerHTML = '<i class="material-icons">favorite_border</i>';
    }
}

//FONCTION QUI MODIFIE LE LIKE SUR LA PAGE PLAYLIST
export function likeMusicPlaylist(data){
    let buttonModif = document.getElementById('likePlaylist_'+data[0]);
    if(data[1] == 1){
        buttonModif.innerHTML = '<i class="material-icons">favorite</i>';
    }else{
        buttonModif.innerHTML = '<i class="material-icons">favorite_border</i>';
    }
}

//FONCTION QUI MODIFIE LE LIKE SUR LA PAGE ACCUEIL
export function likeMusicAccueil(data) {
    let buttonModif = document.getElementById('like_'+data[0]);
    if(data[1] == 1){
        buttonModif.innerHTML = '<i class="material-icons">favorite</i>';
    }else{
        buttonModif.innerHTML = '<i class="material-icons">favorite_border</i>';
    }
}

//FONCTION QUI MODIFIE LE LIKE SUR LA PAGE ALBUM
export function likeMusicAlbum(data) {
    let buttonModif = document.getElementById('likeAlbum_'+data[0]);
    if(data[1] == 1){
        buttonModif.innerHTML = '<i class="material-icons">favorite</i>';
    }else{
        buttonModif.innerHTML = '<i class="material-icons">favorite_border</i>';
    }
}

export function playAlbum(id_album){
    let id_user = document.getElementById('id_perso').value;
    let data = '?request=playAlbum&idAlbum='+id_album+'&idPerso='+id_user;
    ajaxRequest('GET','php/request.php'+data,playAlbumResponse);
}

export function playPlaylist(id_playlist){
    let id_user = document.getElementById('id_perso').value;
    let data = '?request=playPlaylist&idPlaylist='+id_playlist+'&idPerso='+id_user;
    ajaxRequest('GET','php/request.php'+data,playPlaylistResponse);
}

function playPlaylistResponse(data){
    let id_perso = document.getElementById('id_perso').value;
    ajaxRequest('GET', 'php/request.php?request=getNextMusique&idPerso='+id_perso, nextMusicResponse);
}

function playAlbumResponse(data){
    let id_perso = document.getElementById('id_perso').value;
    ajaxRequest('GET', 'php/request.php?request=getNextMusique&idPerso='+id_perso, nextMusicResponse);
}

//FONCTION QUI PERMET DE LANCER UNE MUSIQUE
export function playMusic(id_music) {
    let id_user = document.getElementById('id_perso').value;
    ajaxRequest('GET','php/request.php?request=playMusic&idMusic='+id_music+'&idPerso='+id_user,playMusicResponse);
}

//FONCTION QUI PERMET DE LANCER UNE MUSIQUE (AFFICHAGE)
function playMusicResponse(data) {
    let imgMusic = document.getElementById('imgMusic');
    let titleMusic = document.getElementById('titleMusic');
    let artistMusic = document.getElementById('artistMusic');
    let buttonLiked = document.getElementById('buttonLiked');
    let musicPlaying = document.getElementById('musicPlaying');

    let playMusic = document.getElementById('playMusic');

    imgMusic.src = data[0]['album_chemin'];
    titleMusic.textContent = data[0]['music_title'];
    
    musicPlaying.src = data[0]['music_play_chemin'];
    if(data[0].isliked == 1){
        buttonLiked.innerHTML = '<i class="material-icons">favorite</i>';
    }else{
        buttonLiked.innerHTML = '<i class="material-icons">favorite_border</i>';
    }
    playMusic.innerHTML = '<i class="material-icons">pause</i>';

    musicPlaying.play();
    let id_user = document.getElementById('id_perso').value;
    let dataSend = 'request=upDateHistory&idMusic='+data[0]['music_id']+'&idPerso='+id_user;
    ajaxRequest('PUT','php/request.php',updateNbPlay,dataSend);
}

//FONCTION QUI PERMET DE RAFFRAICHIR LA PAGE ACCUEIL LORS DE LA MUSIQUE
function updateNbPlay(data) {
    let lastEcoute = document.querySelectorAll('.lastEcoute');
    if(lastEcoute.length > 0){
        getLastEcoute();
    }
}

//FONCTION QUI AJOUTE LES MODALS
export function addModal($id_music){
    let id_user = document.getElementById('id_perso').value;
    ajaxRequest('GET','php/request.php?request=playlistWithMusic&id_perso='+id_user+'&idMusic='+$id_music,addModalDisplay);
}

//FONCTION QUI AJOUTE LES MODALS (AFFICHAGE)
function addModalDisplay(data){
    let container = document.getElementById('container');
    let modal = document.createElement('div');
    modal.classList.add('modal');
    modal.id = 'modalAlbum_' + data[1];
    modal.setAttribute('data-bs-backdrop', 'static');
    modal.setAttribute('tabindex', '-1');
    modal.setAttribute('role', 'dialog');
    modal.setAttribute('aria-labelledby', 'exampleModalLabel');

    let modalDialog = document.createElement('div');
    modalDialog.classList.add('modal-dialog');
    modalDialog.setAttribute('role', 'document');

    let modalContent = document.createElement('div');
    modalContent.classList.add('modal-content');

    let modalHeader = document.createElement('div');
    modalHeader.classList.add('modal-header');

    let modalTitle = document.createElement('h5');
    modalTitle.classList.add('modal-title');
    modalTitle.id = 'exampleModalLabel';
    modalTitle.innerHTML = '<span class="material-symbols-outlined">library_add</span>&nbsp AJOUT À UNE PLAYLIST';

    let closeButton = document.createElement('button');
    closeButton.setAttribute('type', 'button');
    closeButton.classList.add('close');
    closeButton.setAttribute('data-bs-dismiss', 'modal');
    closeButton.setAttribute('aria-label', 'Close');
    closeButton.innerHTML = '<span aria-hidden="true">&times;</span>';

    modalHeader.appendChild(modalTitle);
    modalHeader.appendChild(closeButton);

    let size = data[0].length;

    let modalBody = document.createElement('div');
    modalBody.classList.add('modal-body');

    for (let i = 0; i < size; i++) {
        let row = document.createElement('div');
        row.classList.add('row', 'my-2'); // Ajout de la classe 'my-2' pour ajouter de l'espace vertical

        let col1 = document.createElement('div');
        col1.classList.add('col-6', 'center');
        let playlistName = document.createElement('h5');
        playlistName.textContent = data[0][i]['playlist_name'];
        col1.appendChild(playlistName);

        let col2 = document.createElement('div');
        col2.classList.add('col-6', 'center');
        let addButton = document.createElement('button');
        addButton.setAttribute('type', 'button');
        addButton.id = 'addPlaylistAlbumModal' + data[0][i]['playlist_id']+data[1];
        addButton.classList.add('btn', 'btn-danger','colorRed');
        if(data[0][i]['isinplaylist'] == true){
            addButton.innerHTML = '<i class="material-icons">delete</i>';
        }
        else{
            addButton.innerHTML = '<i class="material-icons">add</i>';
        }
        col2.appendChild(addButton);


        addButton.addEventListener('click', function () {
            let id_playlist = data[0][i].playlist_id;
            let id_music = data[1];
            addPlaylist(id_playlist,id_music,'album');
        });
        row.appendChild(col1);
        row.appendChild(col2);
        modalBody.appendChild(row);
    }

    modalContent.appendChild(modalHeader);
    modalContent.appendChild(modalBody);
    modalDialog.appendChild(modalContent);
    modal.appendChild(modalDialog);

    container.appendChild(modal);
}

//FONCTION QUI AJOUTE UNE PLAYLIST AJAX
function addPlaylist(id_playlist,id_music,page){
    let id_user = document.getElementById('id_perso').value;
    let data = 'request=addToPlaylist&idPlaylist='+id_playlist+'&idMusic='+id_music+'&idPerso='+id_user;
    if(page == 'album'){
        ajaxRequest('POST','php/request.php',updatePlaylistAlbum,data);
    }
}

//FONCTION QUI UPDATE L'AFFICHAGE DES ALBUMS
function updatePlaylistAlbum(data){
    let buttonModal = document.getElementById('addPlaylistAlbumModal'+data[1]+data[0]);
    if(data[2]==true){
        buttonModal.innerHTML = '<i class="material-icons">delete</i>';
    }else{
        buttonModal.innerHTML = '<i class="material-icons">add</i>';
    }

}

//FONCCTION QUI DELETE UNE MUSIQUE D'UNE PLAYLIST AJAX
export function deleteMusic(id_music,id_playlist){
    ajaxRequest('DELETE','php/request.php?request=deleteMusicFromPlaylist&idMusic='+id_music+'&idPlaylist='+id_playlist,deleteMusicDisplay);
}

//FONCTION QUI UPDATE L'AFFICHAGE DES MUSIQUES D'UNE PLAYLIST APRES SUPPRESSION
function deleteMusicDisplay(data){
    displayOnePlaylist(data);
}

//FONCTION AJAX QUI AJOUTE UNE NOUVELLE PLAYLIST
export function addNewPlaylist(id_user){

}