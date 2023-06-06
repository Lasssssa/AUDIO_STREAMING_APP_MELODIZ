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

//LANCEMENT DU JEU : 

let game = document.getElementById('game');

game.addEventListener('click', function() {
    startGame();
    }
);

function startGame(){
    let id_Perso = document.getElementById('id_perso').value;
    ajaxRequest('GET', 'php/request.php?request=getGame&idPerso='+id_Perso, startGameResponse);
    scoreValue = 0;
}

let scoreValue = 0;
function startGameResponse(data,i=0,find=true){
    console.log(data);
    if(find==false){
        scoreValue -=1;
    }
    let id_perso = document.getElementById('id_perso').value;
    let container = document.getElementById('container');
    container.innerHTML = '';
    let game = document.createElement('div');
    game.setAttribute('id', 'gameDiv');
    game.setAttribute('style', 'display: flex; flex-direction: column; justify-content: center; align-items: center; row-gap: 20px;margin-right:10%;margin-left:10%;');
    container.appendChild(game);

    let gameTitle = document.createElement('h1');
    gameTitle.setAttribute('id', 'gameTitle');
    gameTitle.innerHTML = 'BLIND TEST';
    game.appendChild(gameTitle);

    let gameScore = document.createElement('h2');
    gameScore.setAttribute('id', 'gameScore');
    gameScore.innerHTML = 'Score :';
    game.appendChild(gameScore);

    let gameScoreValue = document.createElement('input');
    gameScoreValue.setAttribute('id', 'gameScoreValue');
    gameScoreValue.classList.add('form-control');
    gameScoreValue.setAttribute('type', 'text');
    gameScoreValue.setAttribute('value', scoreValue);
    gameScoreValue.setAttribute('readonly', 'true');
    game.appendChild(gameScoreValue);

    let musicNumber = document.createElement('h2');
    musicNumber.setAttribute('id', 'musicNumber');
    musicNumber.innerHTML = 'Musique n°'+i;
    game.appendChild(musicNumber);

    let gameMusic = document.createElement('audio');
    gameMusic.setAttribute('id', 'gameMusic');
    gameMusic.setAttribute('controls', 'true');
    gameMusic.setAttribute('autoplay', 'true')
    gameMusic.setAttribute('autoplay', 'true');
    gameMusic.setAttribute('src', data[i].music_play_chemin);
    game.appendChild(gameMusic);


    let inputText = document.createElement('input');
    inputText.setAttribute('id', 'inputText');
    inputText.classList.add('form-control');
    inputText.setAttribute('type', 'text');
    inputText.setAttribute('placeholder', 'Titre de la musique');
    game.appendChild(inputText);

    let inputArtist = document.createElement('input');
    inputArtist.setAttribute('id', 'inputArtist');
    inputArtist.classList.add('form-control');
    inputArtist.setAttribute('type', 'text');
    inputArtist.setAttribute('placeholder', 'Artiste de la musique (bonus)');
    game.appendChild(inputArtist);

    let submitReponse = document.createElement('button');
    submitReponse.setAttribute('id', 'submitReponse');
    submitReponse.setAttribute('class', 'btn btn-danger','colorRed');
    submitReponse.innerHTML = 'Valider';
    game.appendChild(submitReponse);

    let messageResponse = document.createElement('div');
    messageResponse.setAttribute('id', 'messageResponse');
    game.appendChild(messageResponse);

    submitReponse.addEventListener('click', function() {
        let reponse = document.getElementById('inputText').value;
        let messageResponse = document.getElementById('messageResponse');
        messageResponse.innerHTML = '';
        if(reponse != data[i].music_title){
            let alert = document.createElement('div');
            alert.setAttribute('id', 'alert');
            alert.setAttribute('class', 'alert alert-danger');
            alert.setAttribute('role', 'alert');
            alert.innerHTML = 'Mauvaise réponse !';
            messageResponse.appendChild(alert);
        }else{
            let alert = document.createElement('div');
            alert.setAttribute('id', 'alert');
            alert.setAttribute('class', 'alert alert-success');
            alert.setAttribute('role', 'alert');
            alert.innerHTML = 'Bonne réponse !';
            messageResponse.appendChild(alert);
            scoreValue+=1;
            let artiste = document.getElementById('inputArtist').value;
            let album = document.getElementById('inputAlbum').value;
            if(artiste == data[i].artiste_name || artiste == data[i].artiste_lastname){
                scoreValue+=1;
                console.log('artiste');
            }
            document.getElementById('gameScoreValue').value = scoreValue;
            startGameResponse(data, i+1);
        }
    });






}
