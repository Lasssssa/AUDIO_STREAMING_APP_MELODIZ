import { ajaxRequest } from './ajax.js';
import {getLastEcoute, getOneAlbum, getCurrentUser, getSearchMusic, getSearchAlbum, getSearchArtist, getAccountFriend} from './get.js';
import {playMusic} from './update.js'
import {} from './display.js';
import {displayAccount} from './perso.js';

//LANCE LA FONCTION AU CHARGEMENT DE LA PAGE
getLastEcoute();
  

//RECUPERE LES MUSIC EN COURS--------------------------------------
let playMusic1 = document.getElementById('playMusic');
let musicPlaying = document.getElementById('musicPlaying');
// console.log(playMusic);

playMusic1.addEventListener('click', function() {
  if(musicPlaying.paused){
    musicPlaying.play();
    playMusic1.innerHTML = '<i class="material-icons">pause</i>';
    }else{
    musicPlaying.pause();
    playMusic1.innerHTML = '<i class="material-icons">play_arrow</i>';
    }
});

//CHANGEMENT DU VOLUME--------------------------------------
let changeVolume = document.getElementById('changeVolume');

changeVolume.addEventListener('input', function() {
    musicPlaying.volume = changeVolume.value/100;
    }
);

//MUTE DE LA MUSIQUE ---------------------------------------------
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

//RESET DE LA PAGE --> ACCUEIL ---------------------------------------------

let reset = document.getElementById('reset');

reset.addEventListener('click', function() {
    getLastEcoute();
    }
);

//RECHERCHE D'UNE MUSIQUE, ALBUM OU ARTISTE ---------------------------------------------

let recherche = document.getElementById('recherche');
let rechercheText = document.getElementById('rechercheText');

let choice = document.getElementById('choice');

rechercheText.addEventListener('input', function() {
    if(rechercheText.value != ""){
        if(choice.value == "album"){
            getSearchAlbum(rechercheText.value);
        }
        else if(choice.value == "musique"){
            getSearchMusic(rechercheText.value);
        }
        else if(choice.value == "artiste"){
            getSearchArtist(rechercheText.value);
        }
    }else{
        getLastEcoute();
    }
    }
);

//AJOUT D'UNE PLAYLIST

let addPlaylistButton = document.getElementById('addPlaylistButton');

addPlaylistButton.addEventListener('click', function() {
    let id_user = document.getElementById('id_perso').value;
    let name = document.getElementById('namePlaylist').value;
    let data = 'request=addPlaylist&name='+name+'&idPerso='+id_user;
    ajaxRequest('POST', 'php/request.php/playlist', addPlaylistResponse,data);
    }
);


//LISTENER SUR LE BOUTON COMPTE
let persoAccount = document.getElementById('persoAccount');

persoAccount.addEventListener('click', function() {
    let id_perso = document.getElementById('id_perso').value;
    ajaxRequest('GET', 'php/request.php?request=getUser&idPerso='+id_perso, displayAccount);
    }
);

//LISTENER SUR LE BOUTON AMIS
let friend = document.getElementById('friend');

friend.addEventListener('click', function() {
    let id_perso = document.getElementById('id_perso').value;
    getAccountFriend(id_perso);
    }
);


//AUDIO - AJOUT DES LISTENER POUR TENIR AU COURANT LA PROGRESSE BAR-----------------------------------------------------------
const audio = document.getElementById('musicPlaying');
const progressBar = document.getElementById('progressMusic');

const startLabel = document.getElementById('start');
const endLabel = document.getElementById('end');

audio.addEventListener('timeupdate', function() {
  const currentTime = formatTime(audio.currentTime);
  const duration = formatTime(audio.duration);

  startLabel.textContent = currentTime;
  endLabel.textContent = duration;
});


audio.addEventListener('timeupdate', function() {
  const progress = (audio.currentTime / audio.duration) * 100;
  progressBar.value = progress;
});

progressBar.addEventListener('input', function() {
    const progressValue = progressBar.value;
    const duration = audio.duration;
    const currentTime = (progressValue / 100) * duration;
  
    audio.currentTime = currentTime;
  });

  audio.addEventListener('ended', function() {
    const id_perso = document.getElementById('id_perso').value;
    ajaxRequest('GET', 'php/request.php?request=getNextMusique&idPerso=' + id_perso, nextMusicResponse);
  });

function addPlaylistResponse(data){
    getLastEcoute();
}

function formatTime(time) {
    const minutes = Math.floor(time / 60);
    const seconds = Math.floor(time % 60);
    return `${minutes}:${padZero(seconds)}`;
  }
  
  function padZero(number) {
    return number.toString().padStart(2, '0');
  }


// -------------------------------------------------------Ajout du listener sur le bouton de lecture (previous, play, next)---------------------------------------------

let previousMusic = document.getElementById('previousMusic');

previousMusic.addEventListener('click', function() {
    let id_perso = document.getElementById('id_perso').value;
    ajaxRequest('GET', 'php/request.php?request=getLastMusique&idPerso='+id_perso, previousMusicResponse);
    }
);

function previousMusicResponse(data){
    if(data == "false"){
        getLastEcoute();
    }else{
        playMusic(data.music_id);
    }
}

let nextMusic = document.getElementById('nextMusic');

nextMusic.addEventListener('click', function() {
    let id_perso = document.getElementById('id_perso').value;
    ajaxRequest('GET', 'php/request.php?request=getNextMusique&idPerso='+id_perso, nextMusicResponse);
    }
);

export function nextMusicResponse(data){
    playMusic(data.music_id);
}