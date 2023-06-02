import { playMusic, likeMusic,addModal, addModalMore, deleteMusic} from './update.js';
import { getPlaylistAccueil, getOneArtist, getOneAlbum} from './get.js';
import { ajaxRequest } from './ajax.js';

export function displayLastEcoute(data) {
    // console.log(data);
    let container = document.getElementById('container');
    container.innerHTML = "";

    let lastEcouteDiv = document.createElement('div');
    lastEcouteDiv.classList.add('lastEcoute');
    lastEcouteDiv.style.margin = '15px 0px';

    let titleHeading = document.createElement('h2');
    titleHeading.innerHTML = '<span class="material-symbols-outlined">history</span>&nbspÉcoutés Récemments';
    lastEcouteDiv.appendChild(titleHeading);

    if (data.length > 0) {
        let sliderDiv = document.createElement('div');
        sliderDiv.classList.add('d-flex', 'flex-row', 'sliderTest');

        let size = data.length;
        for (let i = 0; i < size; i++) {
            let id = data[i].music_id;
            let title = data[i].music_title;
            let artist_id = data[i].artiste_id;
            let artist = data[i].artiste_name;
            let artist_lastname = data[i].artiste_lastname;
            let album = data[i].album_title;
            if (artist_lastname == null) {
                artist_lastname = "";
            }

            let cardDiv = document.createElement('div');
            cardDiv.classList.add('card', 'onclicktest');
            cardDiv.style.minWidth = '250px';
            cardDiv.style.minHeight = '250px';
            cardDiv.style.maxWidth = '250px';
            cardDiv.style.margin = '0px 15px';

            let image = document.createElement('img');
            image.classList.add('card-img-top');
            image.src = 'imgMusic/' + title + '.png';
            image.alt = 'Card image cap';
            image.style.maxWidth = '250px';
            image.style.maxHeight = '250px';
            cardDiv.appendChild(image);

            let id_alb = data[i].id_album;
            image.addEventListener('click', function() {
                getOneAlbum(id_alb);
            });

            let cardBodyDiv = document.createElement('div');
            cardBodyDiv.classList.add('card-body');

            let cardTitle = document.createElement('h5');
            cardTitle.classList.add('card-title');
            cardTitle.textContent = title;
            cardBodyDiv.appendChild(cardTitle);

            let cardText = document.createElement('button');
            cardText.classList.add('card-text','clear');
            cardText.style.margin = '0px 0px 13px 0px';
            cardText.textContent = artist + ' ' + artist_lastname;
            cardBodyDiv.appendChild(cardText);

            cardText.addEventListener('click', function() {
                getOneArtist(artist_id);
            });

            let ecartedDiv = document.createElement('div');
            ecartedDiv.classList.add('ecarted');

            let playButton = document.createElement('button');
            playButton.classList.add('btn', 'btn-primary', 'colorRed');
            playButton.type = 'submit';
            playButton.id = 'play_' + id;
            playButton.innerHTML = '<span class="material-symbols-outlined">play_circle</span>';
            ecartedDiv.appendChild(playButton);

            let likeButton = document.createElement('button');
            likeButton.classList.add('btn', 'btn-primary', 'colorRed');
            likeButton.type = 'submit';
            likeButton.id = 'like_' + id;
            console.log(data[i]);
            if (data[i].isliked == true) {
                likeButton.innerHTML = '<i class="material-icons">favorite</i>';
            } else {
                likeButton.innerHTML = '<span class="material-symbols-outlined">favorite</span>';
            }
            ecartedDiv.appendChild(likeButton);

            cardBodyDiv.appendChild(ecartedDiv);
            cardDiv.appendChild(cardBodyDiv);
            let id_album = data[i].id_album;
            sliderDiv.appendChild(cardDiv);

            playButton.addEventListener('click', function() {
                playMusic(id);
            });

            likeButton.addEventListener('click', function() {
                likeMusic(id,'accueil');
            });

        }

        lastEcouteDiv.appendChild(sliderDiv);
    } else {
        let noEcouteParagraph = document.createElement('p');
        noEcouteParagraph.textContent = "Aucune écoute récente";
        lastEcouteDiv.appendChild(noEcouteParagraph);
    }

    container.appendChild(lastEcouteDiv);

    getPlaylistAccueil();
}

export function displayPlaylist(data) {
    let container = document.getElementById('container');

    let lastEcouteDiv = document.createElement('div');
    lastEcouteDiv.classList.add('lastEcoute');

    let divPlaylist = document.createElement('div');


    divPlaylist.classList.add('d-flex', 'flex-row');
    //Style : écarter les éléments de 50px 
    divPlaylist.style.margin = '15px 0px';
    let titleHeading = document.createElement('h2');
    titleHeading.margin = '0px 25px';
    titleHeading.innerHTML = '<span class="material-symbols-outlined">play_circle</span>&nbspPlaylists';
    divPlaylist.appendChild(titleHeading);

    let buttonAdd = document.createElement('button');
    buttonAdd.classList.add('btn', 'btn-primary', 'colorRed');
    buttonAdd.type = 'submit';
    buttonAdd.id = 'addPlaylist';
    buttonAdd.style.margin = '0px 25px';
    buttonAdd.innerHTML = '<span class="material-symbols-outlined">playlist_add</span>';
    divPlaylist.appendChild(buttonAdd);

    lastEcouteDiv.appendChild(divPlaylist);

    

    if (data.length > 0) {
        let sliderDiv = document.createElement('div');
        sliderDiv.classList.add('d-flex', 'flex-row', 'sliderTest');

        let size = data.length;
        for (let i = 0; i < size; i++) {
            let title = data[i].playlist_name;
            let id = data[i].playlist_id;
            let date = data[i].playlist_creation;
            let havePicture = data[i].havepicture;
            let playlist_title = 'default.png';
            if (havePicture == true) {
                playlist_title = title + '.png';
            }

            let cardDiv = document.createElement('div');
            cardDiv.classList.add('card','onclick');
            cardDiv.id = 'playlist_' + id;
            cardDiv.style.minWidth = '250px';
            cardDiv.style.minHeight = '250px';
            cardDiv.style.maxWidth = '250px';
            cardDiv.style.margin = '0px 15px';

            let image = document.createElement('img');
            image.classList.add('card-img-top');
            image.src = 'playlist/' + playlist_title;
            image.alt = 'Card image cap';
            image.style.maxWidth = '250px';
            image.style.maxHeight = '250px';
            cardDiv.appendChild(image);

            let cardBodyDiv = document.createElement('div');
            cardBodyDiv.classList.add('card-body');

            let cardTitle = document.createElement('h5');
            cardTitle.classList.add('card-title');
            cardTitle.textContent = title;
            cardBodyDiv.appendChild(cardTitle);

            let cardText = document.createElement('p');
            cardText.classList.add('card-text');
            cardText.textContent = date;
            cardBodyDiv.appendChild(cardText);

            cardDiv.appendChild(cardBodyDiv);
            sliderDiv.appendChild(cardDiv);


            cardDiv.addEventListener('click', function() {
                displayOnePlaylist(id);
            });
        }

        lastEcouteDiv.appendChild(sliderDiv);
        }else {
        let noPlaylistParagraph = document.createElement('p');
        noPlaylistParagraph.textContent = "Aucune playlist";
        lastEcouteDiv.appendChild(noPlaylistParagraph);
    }

    container.appendChild(lastEcouteDiv);
}
export function displayOnePlaylist(id_playlist) {
    let id = document.getElementById('id_perso').value;
    ajaxRequest('GET','php/request.php?request=getOnePLaylist&idPlaylist=' + id_playlist+'&idPerso='+id,displayOnePlaylistResponse);
}
export function displayOnePlaylistResponse(data) {
    let container = document.getElementById('container');
    container.innerHTML = '';

    let topPlaylistDiv = document.createElement('div');
    topPlaylistDiv.classList.add('topPlaylist');

    let imgDiv = document.createElement('div');
    imgDiv.classList.add('img');

    let img = document.createElement('img');
    if(data[0].havepicture == false) {
        img.src = 'playlist/default.png';
    } else {
        img.src = 'playlist/' + data[0].playlist_name + '.png';
    }
    img.style.maxWidth = '250px';
    img.style.maxHeight = '250px';
    imgDiv.appendChild(img);

    topPlaylistDiv.appendChild(imgDiv);

    let infoPlaylistDiv = document.createElement('div');
    infoPlaylistDiv.classList.add('infoPlaylist');

    let h1 = document.createElement('h1');
    h1.textContent = data[0].playlist_name;
    infoPlaylistDiv.appendChild(h1);

    let h2 = document.createElement('h2');
    h2.textContent = data[0].user_firstname + ' ' + data[0].user_lastname;
    infoPlaylistDiv.appendChild(h2);

    let dateH2 = document.createElement('h2');
    dateH2.textContent = 'Date: ' + data[0].playlist_creation;
    infoPlaylistDiv.appendChild(dateH2);

    let button = document.createElement('button');
    button.classList.add('btn', 'btn-primary', 'colorRed', 'center');
    button.innerHTML = '<span class="material-symbols-outlined">play_circle</span>&nbsp&nbsp Modifier';
    infoPlaylistDiv.appendChild(button);

    topPlaylistDiv.appendChild(infoPlaylistDiv);

    container.appendChild(topPlaylistDiv);

    let musicDiv = document.createElement('div');
    musicDiv.classList.add('music');

    let headers = ['#', '', 'Titre', 'Artiste', 'Album', 'Durée', 'Date d\'ajout', 'Like', 'Supprimer'];

    for (let header of headers) {
    let headerDiv = document.createElement('div');
    headerDiv.classList.add('center');
    let h2 = document.createElement('h2');
    h2.textContent = header;
    headerDiv.appendChild(h2);
    musicDiv.appendChild(headerDiv);
    }

    container.appendChild(musicDiv);
    console.log(data);
    let size = data.length;
    if(data[0].music_id != null) {
            
        for (let i = 0; i < size; i++) {
            let musicDiv = document.createElement('div');
            musicDiv.classList.add('music');

            let playButton = document.createElement('button');
            playButton.classList.add('btn', 'btn-primary', 'colorRed', 'little', 'center');
            playButton.innerHTML = '<span class="material-symbols-outlined">play_circle</span>';
            musicDiv.appendChild(playButton);

            playButton.addEventListener('click', function() {
                playMusic(data[i].music_id);
            });

            let img = document.createElement('img');
            img.classList.add('center');
            img.src = 'imgMusic/' + data[i].music_title + '.png';
            img.style.maxWidth = '50px';
            img.style.maxHeight = '50px';
            musicDiv.appendChild(img);

            img.addEventListener('click', function() {
                getOneAlbum(data[i].id_album);
            });

            let titleDiv = document.createElement('div');
            titleDiv.classList.add('title', 'center');
            let h2 = document.createElement('h2');
            h2.textContent = data[i].music_title;
            titleDiv.appendChild(h2);
            musicDiv.appendChild(titleDiv);

            titleDiv.addEventListener('click', function() {
                getOneAlbum(data[i].id_album);
            });

            let artistDiv = document.createElement('div');
            artistDiv.classList.add('artist', 'center');
            h2 = document.createElement('h2');
            if(data[i].artiste_lastname == null) {
                h2.textContent = data[i].artiste_name;
            }else {
                h2.textContent =  data[i].artiste_name+ ' '+data[i].artiste_lastname;;
            }
            artistDiv.appendChild(h2);
            artistDiv.addEventListener('click', function() {
                getOneArtist(data[i].artiste_id);
            });

            musicDiv.appendChild(artistDiv);

            let albumDiv = document.createElement('div');
            albumDiv.classList.add('album', 'center');
            h2 = document.createElement('h2');
            h2.textContent = data[i].album_title;
            albumDiv.appendChild(h2);
            albumDiv.addEventListener('click', function() {
                getOneAlbum(data[i].id_album);
            });
            musicDiv.appendChild(albumDiv);

            let durationDiv = document.createElement('div');
            durationDiv.classList.add('duration', 'center');
            h2 = document.createElement('h2');
            h2.textContent = data[i].music_duration;
            durationDiv.appendChild(h2);
            musicDiv.appendChild(durationDiv);

            let dateDiv = document.createElement('div');
            dateDiv.classList.add('date', 'center');
            h2 = document.createElement('h2');
            h2.textContent = data[i].date_ajout;
            dateDiv.appendChild(h2);
            musicDiv.appendChild(dateDiv);

            let likeButton = document.createElement('button');
            likeButton.classList.add('btn', 'btn-primary', 'colorRed', 'like', 'little', 'center');
            likeButton.id = 'likePlaylist_' + data[i].music_id;
            let iTag = document.createElement('i');
            iTag.classList.add('material-icons');

            if(data[i].isliked == 1) {
                iTag.textContent = 'favorite';
            }
            else{
                iTag.textContent = 'favorite_border';
            }
            likeButton.appendChild(iTag);
            musicDiv.appendChild(likeButton);

            likeButton.addEventListener('click', function() {
                likeMusic(data[i].music_id,'playlist');
            });

            let deleteButton = document.createElement('button');
            deleteButton.classList.add('btn', 'btn-primary', 'colorRed', 'delete', 'little', 'center');
            iTag = document.createElement('i');
            iTag.classList.add('material-icons');
            iTag.textContent = 'delete';
            deleteButton.appendChild(iTag);
            musicDiv.appendChild(deleteButton);

            deleteButton.addEventListener('click', function() {
                // console.log('coucou');
                deleteMusic(data[i].music_id,data[0].playlist_id);
            });

            container.appendChild(musicDiv);
        }
    }
}
export function displayOneArtistResponse(data){
    console.log(data);
    let container = document.getElementById('container');
    container.innerHTML = '';

    let name;
    let id = data[0].artiste_id;
    if (data[0].artiste_lastname == null) {
        name = data[0].artiste_name;
    } else {
        name = data[0].artiste_name + ' ' + data[0].artiste_lastname;
    }

    let topPlaylistDiv = document.createElement('div');
    topPlaylistDiv.classList.add('topPlaylist');

    let imgDiv = document.createElement('div');
    imgDiv.classList.add('img');

    let img = document.createElement('img');
    img.src = 'imgArtist/' + name + '.png';
    img.style.maxWidth = '250px';
    img.style.maxHeight = '250px';
    imgDiv.appendChild(img);
    topPlaylistDiv.appendChild(imgDiv);

    let infoPlaylistDiv = document.createElement('div');
    infoPlaylistDiv.classList.add('infoPlaylist');

    let h1 = document.createElement('h1');
    h1.textContent = name;
    infoPlaylistDiv.appendChild(h1);

    let h2 = document.createElement('h2');
    h2.textContent = data[0].artiste_type;
    infoPlaylistDiv.appendChild(h2);

    topPlaylistDiv.appendChild(infoPlaylistDiv);
    container.appendChild(topPlaylistDiv);

    let allDiv = document.createElement('div');
    allDiv.classList.add('all');

    let h1All = document.createElement('h1');
    h1All.textContent = "TOP 3 des musiques les plus écoutées";
    allDiv.appendChild(h1All);
    container.appendChild(allDiv);
    
    let musicArtistDiv = document.createElement('div');
    musicArtistDiv.classList.add('musicArtist');


    let divTOP3 = document.createElement('div');
    divTOP3.classList.add('top3');
    divTOP3.id = 'top3';

    container.appendChild(divTOP3);

    ajaxRequest('GET','php/request.php?request=getTop3&idArtist='+id,displayOneArtistResponse2);

    let albumPartDiv = document.createElement('div');
    albumPartDiv.classList.add('albumPart');

    let h1Album = document.createElement('h1');
    h1Album.textContent = "Albums";
    albumPartDiv.appendChild(h1Album);
    container.appendChild(albumPartDiv);

    let sliderTestDiv = document.createElement('div');
    sliderTestDiv.classList.add('d-flex', 'flex-row', 'sliderTest');

    for (let i = 0; i < data.length; i++) {
        let album = data[i].album_title;
        let artist = data[i].artiste_name;
        let imgSrc = 'imgAlbum/Dans l espace.png';
        let style = data[i].album_style;

        let cardDiv = document.createElement('div');
        cardDiv.classList.add('card', 'onclicktest', 'center');
        cardDiv.style.minWidth = '250px';
        cardDiv.style.minHeight = '250px';
        cardDiv.style.maxWidth = '250px';
        cardDiv.style.margin = '0px 15px';

        let cardImg = document.createElement('img');
        cardImg.classList.add('card-img-top');
        cardImg.src = imgSrc;
        cardImg.style.maxWidth = '250px';
        cardImg.style.maxHeight = '250px';
        cardDiv.appendChild(cardImg);

        let cardBodyDiv = document.createElement('div');
        cardBodyDiv.classList.add('card-body');

        let cardTitle = document.createElement('h5');
        cardTitle.classList.add('card-title');
        cardTitle.textContent = album;
        cardBodyDiv.appendChild(cardTitle);

        let cardText = document.createElement('p');
        cardText.classList.add('card-text', 'centerText');
        cardText.innerHTML = '<p>' + artist + ' - ' + style + '</p>';
        cardBodyDiv.appendChild(cardText);

        cardDiv.appendChild(cardBodyDiv);
        sliderTestDiv.appendChild(cardDiv);
    }

    container.appendChild(sliderTestDiv);
}

export function displayOneArtistResponse2(data){
    // console.log(data);
    let container = document.getElementById('top3');
    container.innerHTML = '';
    let html = ''; 
    html += '<div class="music">';
    html += '<div class="center"><h2>#</h2></div>'; 
    html += '<div class="center"><span class="material-symbols-outlined">image</span></div>';
    html += '<div class="center"><h2>Titre</h2></div>';
    html += '<div class="center"><h2>Artiste</h2></div>';
    html += '<div class="center"><h2>Album</h2></div>';
    html += '<div class="center"><h2>Durée</h2></div>';
    html += '<div class="center"><h2>Album Création</h2></div>';
    html += '<div class="center"><h2>Like</h2></div>';
    html += '<div class="center"><h2>Playlist</h2></div>'
    html += '</div>';

    for (let i = 0; i < data.length; i++) {
        //Data contient 3 musiques
        let title_music = data[i].music_title;
        let name_artiste;
        if(data[i].artiste_lastname == null){
            name_artiste = data[i].artiste_name;
        }
        else{
            name_artiste = data[i].artiste_name + ' ' + data[i].artiste_lastname;
        }
        let name_album = data[i].album_title;
        let duree = data[i].music_duration;
        let date = data[i].album_creation;
        html += '<div class="music">';
        html += '<div class="center"><span class="material-symbols-outlined">play_circle</span></div>';
        html += '<img src="imgMusic/' + title_music + '.png" alt="image artiste" style="max-width:50px;max-height:50px">';
        html += '<div class="center"><h2>' + title_music + '</h2></div>';
        html += '<div class="center"><h2>' + name_artiste + '</h2></div>';
        html += '<div class="center"><h2>' + name_album + '</h2></div>';
        html += '<div class="center"><h2>' + duree + '</h2></div>';
        html += '<div class="center"><h2>' + date + '</h2></div>';
        html += '<div class="center"><button class="btn btn-primary colorRed"><span class="material-symbols-outlined">favorite_border</span></button></div>';
        html += '<div class="center"><button class="btn btn-primary colorRed"><span class="material-symbols-outlined">add</span></button></div>';
        html += '</div>';
    }
    container.innerHTML = html;

}

export function displayOneAlbumResponse(data){
    // console.log(data);
    
    let container = document.getElementById('container');
    container.innerHTML = '';

    // Création de la structure de playlist
    let topPlaylistDiv = document.createElement('div');
    topPlaylistDiv.className = 'topPlaylist';

    let imgDiv = document.createElement('div');
    imgDiv.className = 'img';

    let img = document.createElement('img');
    img.src = 'playlist/default.png';
    img.style.maxWidth = '250px';
    img.style.maxHeight = '250px';
    imgDiv.appendChild(img);

    topPlaylistDiv.appendChild(imgDiv);

    let infoPlaylistDiv = document.createElement('div');
    infoPlaylistDiv.className = 'infoPlaylist';

    let h1 = document.createElement('h1');
    h1.textContent = data[0].album_title;
    infoPlaylistDiv.appendChild(h1);

    let h2 = document.createElement('h2');
    h2.textContent = data[0].artiste_name;
    infoPlaylistDiv.appendChild(h2);

    let dateH2 = document.createElement('h2');
    dateH2.textContent = 'Date de parution: ' + data[0].album_creation;
    infoPlaylistDiv.appendChild(dateH2);

    let styleH2 = document.createElement('h2');
    styleH2.textContent = 'Style: ' + data[0].album_style;
    infoPlaylistDiv.appendChild(styleH2);

    topPlaylistDiv.appendChild(infoPlaylistDiv);
    container.appendChild(topPlaylistDiv);

    // Création de la liste des musiques d'artistes
    let musicArtistDiv = document.createElement('div');
    musicArtistDiv.className = 'musicArtist';

    let headers = ['#', 'Image', 'Titre', 'Artiste', 'Durée', 'Like', 'Ajouter'];

    for (let headerText of headers) {
    let headerDiv = document.createElement('div');
    headerDiv.className = 'center';
    let h2 = document.createElement('h2');
    h2.textContent = headerText;
    headerDiv.appendChild(h2);
    musicArtistDiv.appendChild(headerDiv);
    }
    container.appendChild(musicArtistDiv);

    for (let i = 0; i < data.length; i++) {
    let artist_lastname = data[i].artiste_lastname;
    let name = '';
    if (artist_lastname == null) {
        name = data[i].artiste_name;
    } else {
        name = data[i].artiste_name + ' ' + artist_lastname;
    }

    let musicArtistItemDiv = document.createElement('div');
    musicArtistItemDiv.className = 'musicArtist';

    let playButton = document.createElement('button');
    playButton.className = 'btn btn-primary colorRed little center';
    playButton.id = 'play_album' + data[i].music_id;
    let playIcon = document.createElement('span');
    playIcon.className = 'material-icons';
    playIcon.textContent = 'play_arrow';
    playButton.appendChild(playIcon);
    musicArtistItemDiv.appendChild(playButton);

    playButton.addEventListener('click', function () {
        let music_id = data[i].music_id;
        playMusic(music_id);
    });

    let imgDiv = document.createElement('div');
    imgDiv.className = 'center';
    let img = document.createElement('img');
    img.src = 'imgMusic/' + data[i].music_title + '.png';
    img.alt = 'image artiste';
    img.style.maxWidth = '50px';
    img.style.maxHeight = '50px';
    imgDiv.appendChild(img);
    musicArtistItemDiv.appendChild(imgDiv);

    let titleDiv = document.createElement('div');
    titleDiv.className = 'center';
    let h2 = document.createElement('h2');
    h2.textContent = data[i].music_title;
    titleDiv.appendChild(h2);
    musicArtistItemDiv.appendChild(titleDiv);

    let artistDiv = document.createElement('div');
    artistDiv.className = 'center';
    h2 = document.createElement('h2');
    h2.textContent = name;
    artistDiv.appendChild(h2);
    musicArtistItemDiv.appendChild(artistDiv);

    h2.addEventListener('click', function () {
        console.log('test');
        let artist_id = data[i].artiste_id;
        getOneArtist(artist_id);
    });

    let durationDiv = document.createElement('div');
    durationDiv.className = 'center';
    h2 = document.createElement('h2');
    h2.textContent = data[i].music_duration;
    durationDiv.appendChild(h2);
    musicArtistItemDiv.appendChild(durationDiv);

    let likeButton = document.createElement('button');
    likeButton.className = 'btn btn-primary colorRed little center';
    likeButton.id = 'likeAlbum_' + data[i].music_id;
    let likeIcon = document.createElement('span');
    likeIcon.className = 'material-icons';
    likeIcon.textContent = data[i].isliked == 1 ? 'favorite' : 'favorite_border';
    likeButton.appendChild(likeIcon);
    musicArtistItemDiv.appendChild(likeButton);

    likeButton.addEventListener('click', function () {
        let music_id = data[i].music_id;
        likeMusic(music_id,'album');
    });

    let addButton = document.createElement('button');
    addButton.className = 'btn btn-primary colorRed little center';
    addButton.id = 'addAlbum_' + data[i].music_id;
    let addIcon = document.createElement('span');
    addIcon.className = 'material-icons';
    addIcon.textContent = 'add';
    addButton.appendChild(addIcon);
    addButton.setAttribute('data-bs-toggle', 'modal');
    addButton.setAttribute('data-bs-target', '#modalAlbum_'+data[i].music_id+'');
    musicArtistItemDiv.appendChild(addButton);

    addModal(data[i].music_id);

    container.appendChild(musicArtistItemDiv);
    }
    
    

}