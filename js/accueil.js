import { ajaxRequest } from './ajax.js';
import {getLastEcoute, getOneAlbum} from './get.js';
import {} from './update.js'
import {} from './display.js';

getLastEcoute();
  

let playMusic = document.getElementById('playMusic');
let musicPlaying = document.getElementById('musicPlaying');
console.log(playMusic);

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