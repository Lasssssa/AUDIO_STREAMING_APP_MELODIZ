import { ajaxRequest } from './ajax.js';
import {getLastEcoute, getOneAlbum, getCurrentUser, getSearchMusic} from './get.js';
import {} from './update.js'
import {} from './display.js';
import {displayAccount} from './perso.js';

getLastEcoute();
  

let playMusic = document.getElementById('playMusic');
let musicPlaying = document.getElementById('musicPlaying');
// console.log(playMusic);

playMusic.addEventListener('click', function() {
  if(musicPlaying.paused){
    musicPlaying.play();
    playMusic.innerHTML = '<i class="material-icons">pause</i>';
    }else{
    musicPlaying.pause();
    playMusic.innerHTML = '<i class="material-icons">play_arrow</i>';
    }
});

let changeVolume = document.getElementById('changeVolume');

changeVolume.addEventListener('input', function() {
    musicPlaying.volume = changeVolume.value/100;
    }
);

let mute = document.getElementById('mute');

mute.addEventListener('click', function() {
    if(musicPlaying.muted){
        musicPlaying.muted = false;
        mute.innerHTML = '<i class="material-icons">volume_up</i>';
    }else{
        musicPlaying.muted = true;
        mute.innerHTML = '<i class="material-icons">volume_off</i>';
    }
    }
);

let reset = document.getElementById('reset');

reset.addEventListener('click', function() {
    getLastEcoute();
    }
);

let recherche = document.getElementById('recherche');
let rechercheText = document.getElementById('rechercheText');

recherche.addEventListener('click', function() {
    if(rechercheText.value != ""){
        getSearchMusic(rechercheText.value);
    }
    }
);

let addPlaylistButton = document.getElementById('addPlaylistButton');

addPlaylistButton.addEventListener('click', function() {
    let id_user = document.getElementById('id_perso').value;
    let name = document.getElementById('namePlaylist').value;
    let data = 'request=addPlaylist&name='+name+'&idPerso='+id_user;
    ajaxRequest('POST', 'php/request.php/playlist', addPlaylistResponse,data);
    }
);

let persoAccount = document.getElementById('persoAccount');

persoAccount.addEventListener('click', function() {
    let id_perso = document.getElementById('id_perso').value;
    ajaxRequest('GET', 'php/request.php?request=getUser&idPerso='+id_perso, displayAccount);
    }
);

function addPlaylistResponse(data){
    console.log(data);
}
