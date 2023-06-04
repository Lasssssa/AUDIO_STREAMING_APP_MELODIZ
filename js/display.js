import { playMusic, likeMusic,addModal, deleteMusic, addNewPlaylist} from './update.js';
import { getPlaylistAccueil, getOneArtist, getOneAlbum,getSearchAlbum ,getSearchArtist,getSearchMusic} from './get.js';
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
            // console.log(data[i].album_chemin);
            image.src = data[i].album_chemin;
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

            cardTitle.addEventListener('click', function() {
                getOneAlbum(id_alb);
            });

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
            playButton.classList.add('btn', 'btn-danger', 'colorRed');
            playButton.type = 'submit';
            playButton.id = 'play_' + id;
            playButton.innerHTML = '<span class="material-symbols-outlined">play_circle</span>';
            ecartedDiv.appendChild(playButton);

            let likeButton = document.createElement('button');
            likeButton.classList.add('btn', 'btn-danger', 'colorRed');
            likeButton.type = 'submit';
            likeButton.id = 'like_' + id;
            // console.log(data[i]);
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
    buttonAdd.classList.add('btn', 'btn-danger', 'colorRed');
    buttonAdd.id = 'addPlaylist';
    buttonAdd.style.margin = '0px 25px';
    buttonAdd.setAttribute('data-bs-toggle', 'modal');
    buttonAdd.setAttribute('data-bs-target', '#modalPlaylist');
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
            let playlist_title = data[i].playlist_picture;

            let cardDiv = document.createElement('div');
            cardDiv.classList.add('card','onclick');
            cardDiv.id = 'playlist_' + id;
            cardDiv.style.minWidth = '250px';
            cardDiv.style.minHeight = '250px';
            cardDiv.style.maxWidth = '250px';
            cardDiv.style.margin = '0px 15px';

            let image = document.createElement('img');
            image.classList.add('card-img-top');
            image.src = playlist_title;
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
    img.src = data[0].playlist_picture;
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
    button.classList.add('btn', 'btn-danger', 'colorRed', 'center');
    button.setAttribute('data-bs-toggle', 'modal');
    button.setAttribute('data-bs-target', '#modalModifyPlaylist');
    button.innerHTML = '<span class="material-symbols-outlined">play_circle</span>&nbsp&nbsp Modifier';
    infoPlaylistDiv.appendChild(button);

    addModalModifyPlaylist(data[0].playlist_id,data[0].playlist_name);

    topPlaylistDiv.appendChild(infoPlaylistDiv);

    container.appendChild(topPlaylistDiv);

    let musicDiv = document.createElement('div');
    musicDiv.classList.add('music');

    let headers = ['#', '', 'Titre', 'Artiste', 'Album', 'Durée', 'Date d\'ajout', 'Like', 'Supprimer'];

    for (let header of headers) {
    let headerDiv = document.createElement('div');
    headerDiv.classList.add('center');
    let h2 = document.createElement('h2');
    if(header == '') {
        h2.innerHTML = '<span class="material-symbols-outlined">image</span>';
    }
    else{
        h2.textContent = header;
    }
    headerDiv.appendChild(h2);
    musicDiv.appendChild(headerDiv);
    }

    container.appendChild(musicDiv);
    // console.log(data);
    let size = data.length;
    if(data[0].music_id != null) {
            
        for (let i = 0; i < size; i++) {
            let musicDiv = document.createElement('div');
            musicDiv.classList.add('music');

            let playButton = document.createElement('button');
            playButton.classList.add('btn', 'btn-danger', 'colorRed', 'little', 'center');
            playButton.innerHTML = '<span class="material-symbols-outlined">play_circle</span>';
            musicDiv.appendChild(playButton);

            playButton.addEventListener('click', function() {
                playMusic(data[i].music_id);
            });

            let img = document.createElement('img');
            img.classList.add('center');
            img.src = data[i].album_chemin;
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
            albumDiv.classList.add('center');
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
            likeButton.classList.add('btn', 'btn-danger', 'colorRed', 'like', 'little', 'center');
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
            deleteButton.classList.add('btn', 'btn-danger', 'colorRed', 'delete', 'little', 'center');
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

function addModalModifyPlaylist(id_playlist, playlist_title){
    let container = document.getElementById('container');

    // Création des éléments du modal
    let modalDiv = document.createElement('div');
    modalDiv.classList.add('modal', 'fade');
    modalDiv.id = 'modalModifyPlaylist';
    modalDiv.tabIndex = '1';
    modalDiv.setAttribute('aria-labelledby', 'modalModifyPlaylist');
    modalDiv.setAttribute('aria-hidden', 'true');

    let modalDialogDiv = document.createElement('div');
    modalDialogDiv.classList.add('modal-dialog');

    let modalContentDiv = document.createElement('div');
    modalContentDiv.classList.add('modal-content');

    let modalHeaderDiv = document.createElement('div');
    modalHeaderDiv.classList.add('modal-header');

    let modalTitle = document.createElement('h1');
    modalTitle.classList.add('modal-title', 'fs-5');
    modalTitle.id = 'exampleModalLabel';
    modalTitle.textContent = 'MODIFIER VOTRE PLAYLIST';

    let closeButton = document.createElement('button');
    closeButton.type = 'button';
    closeButton.classList.add('btn-close');
    closeButton.setAttribute('data-bs-dismiss', 'modal');
    closeButton.setAttribute('aria-label', 'Close');

    modalHeaderDiv.appendChild(modalTitle);
    modalHeaderDiv.appendChild(closeButton);

    let modalBodyDiv = document.createElement('div');
    modalBodyDiv.classList.add('modal-body');

    let nameLabel = document.createElement('label');
    nameLabel.classList.add('col-form-label');
    nameLabel.setAttribute('for', 'namePlaylist');
    nameLabel.textContent = 'Nom de la playlist :';

    let nameInput = document.createElement('input');
    nameInput.type = 'text';
    nameInput.classList.add('form-control');
    nameInput.id = 'namePlaylistModal';
    nameInput.value = playlist_title;

    let imageLabel = document.createElement('label');
    imageLabel.classList.add('col-form-label');
    imageLabel.setAttribute('for', 'imagePlaylist');
    imageLabel.textContent = 'Ajouter votre image :';

    let imageInput = document.createElement('input');
    imageInput.type = 'file';
    imageInput.classList.add('form-control');
    imageInput.id = 'imagePlaylist';

    //Création d'un bouton pour supprimer la playlist
    let deleteButton = document.createElement('button');
    deleteButton.type = 'button';
    deleteButton.classList.add('btn', 'btn-danger', 'colorRed');
    deleteButton.style.marginTop = '20px';
    deleteButton.id = 'deletePlaylist';
    deleteButton.textContent = 'Supprimer la playlist';
    
    deleteButton.addEventListener('click', function() {
        deletePlaylist(id_playlist);
    });

    modalBodyDiv.appendChild(nameLabel);
    modalBodyDiv.appendChild(nameInput);
    modalBodyDiv.appendChild(imageLabel);
    modalBodyDiv.appendChild(imageInput);
    modalBodyDiv.appendChild(deleteButton);

    let modalFooterDiv = document.createElement('div');
    modalFooterDiv.classList.add('modal-footer');

    let closeButton2 = document.createElement('button');
    closeButton2.type = 'button';
    closeButton2.classList.add('btn', 'btn-secondary');
    closeButton2.setAttribute('data-bs-dismiss', 'modal');
    closeButton2.textContent = 'Fermer';

    let modifyButton = document.createElement('button');
    modifyButton.type = 'button';
    modifyButton.classList.add('btn', 'btn-danger', 'colorRed');
    modifyButton.id = 'modifyPlaylist';
    modifyButton.textContent = 'Modifier';

    modifyButton.addEventListener('click', function() {
        modifyPlaylist(id_playlist);
    });


    modalFooterDiv.appendChild(closeButton2);
    modalFooterDiv.appendChild(modifyButton);

    modalContentDiv.appendChild(modalHeaderDiv);
    modalContentDiv.appendChild(modalBodyDiv);
    modalContentDiv.appendChild(modalFooterDiv);

    modalDialogDiv.appendChild(modalContentDiv);

    modalDiv.appendChild(modalDialogDiv);

    container.appendChild(modalDiv);

}

function modifyPlaylist(id_playlist){
    let name = document.getElementById('namePlaylistModal').value;
    let image = document.getElementById('imagePlaylist').files[0];
    let id_user = document.getElementById('id_perso').value;
    let request = 'modifyPlaylist';

    let data = 'request='+request+'&id_playlist='+id_playlist+'&name='+name+'&image='+image+'&id_user='+id_user;

    ajaxRequest('POST', 'php/request.php', modifyPlaylistResponse, data);
}

function modifyPlaylistResponse(data){
    if(data == 1){
        window.location.reload();
    }else{
        alert('Une erreur est survenue, vous ne pouvez pas modifier votre playlist !');
    }
    // console.log(data);
}

function deletePlaylist(id_playlist){
    let id_user = document.getElementById('id_perso').value;
    ajaxRequest('DELETE','php/request.php?request=deletePlaylist&id_playlist='+id_playlist+'&id_user='+id_user,deletePlaylistResponse);
}

function deletePlaylistResponse(data){
    if(data == 1){
        window.location.reload();
    }else{
        alert('Une erreur est survenue, vous ne pouvez pas supprimer votre playlist !');
    }
}
export function displayOneArtistResponse(data){
    // console.log(data);
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
    img.src = data[0].artiste_chemin;
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
        let imgSrc = data[i].album_chemin;
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

        cardImg.addEventListener('click', function() {
            getOneAlbum(data[i].id_album);
            // console.log('coucou');
        });

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

    let musicContainer = document.createElement('div');
    musicContainer.classList.add('music');

    let numberHeader = document.createElement('div');
    numberHeader.classList.add('center');
    numberHeader.innerHTML = '<h2>#</h2>';
    musicContainer.appendChild(numberHeader);

    let imageHeader = document.createElement('div');
    imageHeader.classList.add('center');
    imageHeader.innerHTML = '<span class="material-symbols-outlined">image</span>';
    musicContainer.appendChild(imageHeader);

    let titleHeader = document.createElement('div');
    titleHeader.classList.add('center');
    titleHeader.innerHTML = '<h2>Titre</h2>';
    musicContainer.appendChild(titleHeader);

    let artistHeader = document.createElement('div');
    artistHeader.classList.add('center');
    artistHeader.innerHTML = '<h2>Artiste</h2>';
    musicContainer.appendChild(artistHeader);

    let albumHeader = document.createElement('div');
    albumHeader.classList.add('center');
    albumHeader.innerHTML = '<h2>Album</h2>';
    musicContainer.appendChild(albumHeader);

    let durationHeader = document.createElement('div');
    durationHeader.classList.add('center');
    durationHeader.innerHTML = '<h2>Durée</h2>';
    musicContainer.appendChild(durationHeader);

    let albumCreationHeader = document.createElement('div');
    albumCreationHeader.classList.add('center');
    albumCreationHeader.innerHTML = '<h2>Album Création</h2>';
    musicContainer.appendChild(albumCreationHeader);

    let likeHeader = document.createElement('div');
    likeHeader.classList.add('center');
    likeHeader.innerHTML = '<h2>Like</h2>';
    musicContainer.appendChild(likeHeader);

    let playlistHeader = document.createElement('div');
    playlistHeader.classList.add('center');
    playlistHeader.innerHTML = '<h2>Playlist</h2>';
    musicContainer.appendChild(playlistHeader);

    container.appendChild(musicContainer);

    for (let i = 0; i < data.length; i++) {
        let title_music = data[i].music_title;
        let name_artiste;
        if (data[i].artiste_lastname == null) {
            name_artiste = data[i].artiste_name;
        } else {
            name_artiste = data[i].artiste_name + ' ' + data[i].artiste_lastname;
        }
        let name_album = data[i].album_title;
        let duree = data[i].music_duration;
        let date = data[i].album_creation;

        let musicDiv = document.createElement('div');
        musicDiv.classList.add('music');

        let playButton = document.createElement('button');
        playButton.classList.add('btn', 'btn-danger', 'colorRed', 'little', 'center');
        playButton.innerHTML = '<span class="material-symbols-outlined">play_circle</span>';
        musicDiv.appendChild(playButton);

        playButton.addEventListener('click', function () {
            playMusic(data[i].music_id);
        });

        let image = document.createElement('img');
        image.src = data[i].album_chemin;
        image.alt = 'image artiste';
        image.classList.add('center');
        image.style.maxWidth = '50px';
        image.style.maxHeight = '50px';
        musicDiv.appendChild(image);

        image.addEventListener('click', function () {
            getOneAlbum(data[i].id_album);
        });

        let title = document.createElement('div');
        title.classList.add('center');
        title.innerHTML = '<h2>' + title_music + '</h2>';
        musicDiv.appendChild(title);

        title.addEventListener('click', function () {
            getOneAlbum(data[i].id_album);
        });

        let artist = document.createElement('div');
        artist.classList.add('center');
        artist.innerHTML = '<h2>' + name_artiste + '</h2>';
        musicDiv.appendChild(artist);

        let album = document.createElement('div');
        album.classList.add('center');
        album.innerHTML = '<h2>' + name_album + '</h2>';
        musicDiv.appendChild(album);

        album.addEventListener('click', function () {
            getOneAlbum(data[i].id_album);
        });

        let duration = document.createElement('div');
        duration.classList.add('center');
        duration.innerHTML = '<h2>' + duree + '</h2>';
        musicDiv.appendChild(duration);

        let albumCreation = document.createElement('div');
        albumCreation.classList.add('center');
        albumCreation.innerHTML = '<h2>' + date + '</h2>';
        musicDiv.appendChild(albumCreation);

        let likeButton = document.createElement('button');
        likeButton.classList.add('btn', 'btn-danger', 'colorRed', 'like', 'little', 'center');
        likeButton.id = 'likeArtist_' + data[i].music_id;
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
            likeMusic(data[i].music_id,'artiste');
        });    

        let addButton = document.createElement('button');
        addButton.className = 'btn btn-danger colorRed little center';
        addButton.id = 'addAlbum_' + data[i].music_id;
        let addIcon = document.createElement('span');
        addIcon.className = 'material-icons';
        addIcon.textContent = 'add';
        addButton.appendChild(addIcon);
        addButton.setAttribute('data-bs-toggle', 'modal');
        addButton.setAttribute('data-bs-target', '#modalAlbum_'+data[i].music_id+'');
        musicDiv.appendChild(addButton);

        addModal(data[i].music_id);


        container.appendChild(musicDiv);
    }
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
    img.src = data[0].album_chemin;
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

    let headers = ['#', '<span class="material-symbols-outlined">image</span>', 'Titre', 'Artiste', 'Durée', 'Like', 'Ajouter'];

    for (let headerText of headers) {
    let headerDiv = document.createElement('div');
    headerDiv.className = 'center';
    let h2 = document.createElement('h2');
    if(headerText == '<span class="material-symbols-outlined">image</span>'){
        h2.innerHTML = headerText;
    }else{
        h2.textContent = headerText;
    }
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
        playButton.className = 'btn btn-danger colorRed little center';
        playButton.id = 'play_album' + data[i].music_id;
        let playIcon = document.createElement('span');
        playIcon.className = 'material-symbols-outlined';
        playIcon.textContent = 'play_circle';
        playButton.appendChild(playIcon);
        musicArtistItemDiv.appendChild(playButton);

        playButton.addEventListener('click', function () {
            let music_id = data[i].music_id;
            playMusic(music_id);
        });

        let imgDiv = document.createElement('div');
        imgDiv.className = 'center';
        let img = document.createElement('img');
        img.src = data[i].album_chemin;
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
            // console.log('test');
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
        likeButton.className = 'btn btn-danger colorRed little center';
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
        addButton.className = 'btn btn-danger colorRed little center';
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

export function displayRechercheMusic(data){
    let container = document.getElementById('container');
    container.innerHTML = '';

    let rechercheDiv = document.createElement('div');
    rechercheDiv.classList.add('recherche');

    let rechercheTitleDiv = document.createElement('div');
    rechercheTitleDiv.classList.add('rechercheTitle');

    let col12Div = document.createElement('div');
    col12Div.classList.add('col-12');
    let titleH4 = document.createElement('h4');
    titleH4.classList.add('text-center');
    titleH4.textContent = 'Recherche';
    col12Div.appendChild(titleH4);
    rechercheTitleDiv.appendChild(col12Div);
    rechercheDiv.appendChild(rechercheTitleDiv);

    let buttonRechercheDiv = document.createElement('div');
    buttonRechercheDiv.classList.add('buttonRecherche', 'text-center');

    let buttonMusique = document.createElement('button');
    buttonMusique.setAttribute('type', 'button');
    buttonMusique.classList.add('btn', 'btn-danger', 'ecart');
    buttonMusique.setAttribute('id', 'buttonRecherche');
    buttonMusique.textContent = 'Musique';

    buttonMusique.addEventListener('click', function () {
        let recherche = document.getElementById('rechercheText').value;
        // console.log(recherche);
        getSearchMusic(recherche);
    });

    let buttonAlbum = document.createElement('button');
    buttonAlbum.setAttribute('type', 'button');
    buttonAlbum.classList.add('btn', 'btn-danger', 'ecart');
    buttonAlbum.setAttribute('id', 'buttonRecherche');
    buttonAlbum.textContent = 'Album';

    buttonAlbum.addEventListener('click', function () {
        let recherche = document.getElementById('rechercheText').value;
        getSearchAlbum(recherche);
    });

    let buttonArtiste = document.createElement('button');
    buttonArtiste.setAttribute('type', 'button');
    buttonArtiste.classList.add('btn', 'btn-danger', 'ecart');
    buttonArtiste.setAttribute('id', 'buttonRecherche');
    buttonArtiste.textContent = 'Artiste';

    buttonArtiste.addEventListener('click', function () {
        let recherche = document.getElementById('rechercheText').value;
        getSearchArtist(recherche);
    });

    buttonRechercheDiv.appendChild(buttonMusique);
    buttonRechercheDiv.appendChild(buttonAlbum);
    buttonRechercheDiv.appendChild(buttonArtiste);
    rechercheDiv.appendChild(buttonRechercheDiv);

    if(data.length == 0){
        let col12Div = document.createElement('div');
        col12Div.classList.add('col-12','marginTop');
        let titleH4 = document.createElement('h4');
        titleH4.classList.add('text-center');
        titleH4.textContent = 'Aucun résultat';
        col12Div.appendChild(titleH4);
        rechercheDiv.appendChild(col12Div);
        container.appendChild(rechercheDiv);
    }else{

        let musicArDiv = document.createElement('div');
        musicArDiv.classList.add('musicAr');

        let centerDiv1 = document.createElement('div');
        centerDiv1.classList.add('center');
        let h2Center1 = document.createElement('h2');
        h2Center1.textContent = '#';
        centerDiv1.appendChild(h2Center1);

        let centerDiv2 = document.createElement('div');
        centerDiv2.classList.add('center');
        let spanCenter2 = document.createElement('span');
        spanCenter2.classList.add('material-symbols-outlined');
        spanCenter2.textContent = 'image';
        centerDiv2.appendChild(spanCenter2);

        let centerDiv3 = document.createElement('div');
        centerDiv3.classList.add('center');
        let h2Center3 = document.createElement('h2');
        h2Center3.textContent = 'Titre';
        centerDiv3.appendChild(h2Center3);

        let centerDiv4 = document.createElement('div');
        centerDiv4.classList.add('center');
        let h2Center4 = document.createElement('h2');
        h2Center4.textContent = 'Artiste';
        centerDiv4.appendChild(h2Center4);

        let centerDiv5 = document.createElement('div');
        centerDiv5.classList.add('center');
        let h2Center5 = document.createElement('h2');
        h2Center5.textContent = 'Album';
        centerDiv5.appendChild(h2Center5);

        let centerDiv6 = document.createElement('div');
        centerDiv6.classList.add('center');
        let h2Center6 = document.createElement('h2');
        h2Center6.textContent = 'Durée';
        centerDiv6.appendChild(h2Center6);

        let centerDiv7 = document.createElement('div');
        centerDiv7.classList.add('center');
        let h2Center7 = document.createElement('h2');
        h2Center7.textContent = 'Like';
        centerDiv7.appendChild(h2Center7);

        let centerDiv8 = document.createElement('div');
        centerDiv8.classList.add('center');
        let h2Center8 = document.createElement('h2');
        h2Center8.textContent = 'Playlist';
        centerDiv8.appendChild(h2Center8);

        musicArDiv.appendChild(centerDiv1);
        musicArDiv.appendChild(centerDiv2);
        musicArDiv.appendChild(centerDiv3);
        musicArDiv.appendChild(centerDiv4);
        musicArDiv.appendChild(centerDiv5);
        musicArDiv.appendChild(centerDiv6);
        musicArDiv.appendChild(centerDiv7);
        musicArDiv.appendChild(centerDiv8);

        rechercheDiv.appendChild(musicArDiv);

        container.appendChild(rechercheDiv);

        for (let i = 0; i < data.length; i++) {
            let musicArDiv = document.createElement('div');
            musicArDiv.classList.add('musicAr');

            let centerDiv1 = document.createElement('div');
            centerDiv1.classList.add('center');
            let playButton = document.createElement('button');
            playButton.classList.add('btn', 'btn-danger', 'colorRed', 'little', 'center');
            let playSpan = document.createElement('span');
            playSpan.classList.add('material-symbols-outlined');
            playSpan.textContent = 'play_circle';
            playButton.appendChild(playSpan);
            centerDiv1.appendChild(playButton);

            playButton.addEventListener('click', function () {
                let music_id = data[i].music_id;
                playMusic(music_id);
            });

            let centerDiv2 = document.createElement('div');
            centerDiv2.classList.add('center');
            let imgArtist = document.createElement('img');
            imgArtist.setAttribute('src', data[i].album_chemin);
            imgArtist.setAttribute('alt', 'image artiste');
            imgArtist.style.maxWidth = '50px';
            imgArtist.style.maxHeight = '50px';
            centerDiv2.appendChild(imgArtist);

            imgArtist.addEventListener('click', function () {
                let album_id = data[i].id_album;
                getOneAlbum(album_id);
            });

            let centerDiv3 = document.createElement('div');
            centerDiv3.classList.add('center');
            let h2Center3 = document.createElement('h2');
            h2Center3.textContent = data[i].music_title;
            centerDiv3.appendChild(h2Center3);

            h2Center3.addEventListener('click', function () {
                let album_id = data[i].id_album;
                getOneAlbum(album_id);
            });

            let centerDiv4 = document.createElement('div');
            centerDiv4.classList.add('center');
            let artistName = '';
            if (data[i].artiste_lastname == null) {
                artistName = data[i].artiste_name;
            } else {
                artistName = data[i].artiste_name + ' ' + data[i].artiste_lastname;
            }
            let h2Center4 = document.createElement('h2');
            h2Center4.textContent = artistName;
            centerDiv4.appendChild(h2Center4);

            h2Center4.addEventListener('click', function () {
                let artist_id = data[i].artiste_id;
                getOneArtist(artist_id);
            });

            let centerDiv5 = document.createElement('div');
            centerDiv5.classList.add('center');
            let h2Center5 = document.createElement('h2');
            h2Center5.textContent = data[i].album_title;
            centerDiv5.appendChild(h2Center5);

            h2Center5.addEventListener('click', function () {
                let album_id = data[i].id_album;
                getOneAlbum(album_id);
            });

            let centerDiv6 = document.createElement('div');
            centerDiv6.classList.add('center');
            let h2Center6 = document.createElement('h2');
            h2Center6.textContent = data[i].music_duration;
            centerDiv6.appendChild(h2Center6);

            let centerDiv7 = document.createElement('div');
            centerDiv7.classList.add('center');
            let likeButton = document.createElement('button');
            likeButton.classList.add('btn', 'btn-danger', 'colorRed', 'little', 'center');
            likeButton.id = 'likeRecherche_'+data[i].music_id;
            let likeSpan = document.createElement('span');
            if (data[i].isliked == 1) {
                likeSpan.classList.add('material-icons');
                likeSpan.textContent = 'favorite';
            } else {
                likeSpan.classList.add('material-icons');
                likeSpan.textContent = 'favorite_border';
            }
            likeButton.appendChild(likeSpan);
            centerDiv7.appendChild(likeButton);

            likeButton.addEventListener('click', function () {
                let music_id = data[i].music_id;
                likeMusic(music_id,'recherche');
            });


            let centerDiv8 = document.createElement('div');
            centerDiv8.classList.add('center');

            let addButton = document.createElement('button');
            addButton.className = 'btn btn-danger colorRed little center';
            addButton.id = 'addAlbum_' + data[i].music_id;
            let addIcon = document.createElement('span');
            addIcon.className = 'material-icons';
            addIcon.textContent = 'add';
            addButton.appendChild(addIcon);
            addButton.setAttribute('data-bs-toggle', 'modal');
            addButton.setAttribute('data-bs-target', '#modalAlbum_'+data[i].music_id+'');
            centerDiv8.appendChild(addButton);

            addModal(data[i].music_id);

            addModal(data[i].music_id);

            musicArDiv.appendChild(centerDiv1);
            musicArDiv.appendChild(centerDiv2);
            musicArDiv.appendChild(centerDiv3);
            musicArDiv.appendChild(centerDiv4);
            musicArDiv.appendChild(centerDiv5);
            musicArDiv.appendChild(centerDiv6);
            musicArDiv.appendChild(centerDiv7);
            musicArDiv.appendChild(centerDiv8);

            container.appendChild(musicArDiv);
        }
    }
}

export function displayRechercheArtist(data){
    // console.log(data);
    let container = document.getElementById('container');
    container.innerHTML = '';

    let rechercheDiv = document.createElement('div');
    rechercheDiv.classList.add('recherche');

    let rechercheTitleDiv = document.createElement('div');
    rechercheTitleDiv.classList.add('rechercheTitle');

    let col12Div = document.createElement('div');
    col12Div.classList.add('col-12');
    let titleH4 = document.createElement('h4');
    titleH4.classList.add('text-center');
    titleH4.textContent = 'Recherche';
    col12Div.appendChild(titleH4);
    rechercheTitleDiv.appendChild(col12Div);
    rechercheDiv.appendChild(rechercheTitleDiv);

    let buttonRechercheDiv = document.createElement('div');
    buttonRechercheDiv.classList.add('buttonRecherche', 'text-center');

    let buttonMusique = document.createElement('button');
    buttonMusique.setAttribute('type', 'button');
    buttonMusique.classList.add('btn', 'btn-danger', 'ecart');
    buttonMusique.setAttribute('id', 'buttonRecherche');
    buttonMusique.textContent = 'Musique';

    buttonMusique.addEventListener('click', function () {
        let recherche = document.getElementById('rechercheText').value;
        // console.log(recherche);
        getSearchMusic(recherche);
    });

    let buttonAlbum = document.createElement('button');
    buttonAlbum.setAttribute('type', 'button');
    buttonAlbum.classList.add('btn', 'btn-danger', 'ecart');
    buttonAlbum.setAttribute('id', 'buttonRecherche');
    buttonAlbum.textContent = 'Album';

    buttonAlbum.addEventListener('click', function () {
        let recherche = document.getElementById('rechercheText').value;
        getSearchAlbum(recherche);
    });

    let buttonArtiste = document.createElement('button');
    buttonArtiste.setAttribute('type', 'button');
    buttonArtiste.classList.add('btn', 'btn-danger', 'ecart');
    buttonArtiste.setAttribute('id', 'buttonRecherche');
    buttonArtiste.textContent = 'Artiste';

    buttonArtiste.addEventListener('click', function () {
        let recherche = document.getElementById('rechercheText').value;
        getSearchArtist(recherche);
    });

    buttonRechercheDiv.appendChild(buttonMusique);
    buttonRechercheDiv.appendChild(buttonAlbum);
    buttonRechercheDiv.appendChild(buttonArtiste);
    rechercheDiv.appendChild(buttonRechercheDiv);

    if(data.length == 0){
        let col12Div = document.createElement('div');
        col12Div.classList.add('col-12','marginTop');
        let titleH4 = document.createElement('h4');
        titleH4.classList.add('text-center');
        titleH4.textContent = 'Aucun résultat';
        col12Div.appendChild(titleH4);
        rechercheDiv.appendChild(col12Div);
        container.appendChild(rechercheDiv);
    }else{
        for (let i = 0; i < data.length; i++) {
            let albumDiv = document.createElement('div');
            albumDiv.classList.add('album');
            
            let imgAlbum = document.createElement('img');
            imgAlbum.setAttribute('src', data[i].artiste_chemin);
            imgAlbum.setAttribute('alt', 'album');
            imgAlbum.classList.add('img-fluid');
            imgAlbum.style.maxWidth = '150px';
            imgAlbum.style.maxHeight = '150px';
            albumDiv.appendChild(imgAlbum);

            imgAlbum.addEventListener('click', function () {
                let artiste_id = data[i].artiste_id;
                getOneArtist(artiste_id);
            });
            
            let h1Title = document.createElement('h1');
            let name = '';
            if (data[i].artiste_lastname != null) {
                name = data[i].artiste_name + ' ' + data[i].artiste_lastname;
            } else {
                name = data[i].artiste_name;
            }
            h1Title.textContent = name;
            albumDiv.appendChild(h1Title);
            
            h1Title.addEventListener('click', function () {
                let artiste_id = data[i].artiste_id;
                getOneArtist(artiste_id);
            });
            
            let h3Creation = document.createElement('h3');
            let bio = '';
            if (data[i].artiste_bio != null) {
                bio = data[i].artiste_bio;
            } else {
                bio = 'Aucune biographie';
            }
            h3Creation.textContent = bio;
            albumDiv.appendChild(h3Creation);

            let h3Name = document.createElement('h3');
            h3Name.textContent = 'Type : '+data[i].artiste_type;
            albumDiv.appendChild(h3Name);
            
            
            let h3Style = document.createElement('h3');
            h3Style.textContent = 'Album : ' + data[i].albumcount;
            albumDiv.appendChild(h3Style);
            
            rechercheDiv.appendChild(albumDiv);            
        }
        container.appendChild(rechercheDiv);
    }
}

export function displayRechercheAlbum(data){
    // console.log(data);
    let container = document.getElementById('container');
    container.innerHTML = '';

    let rechercheDiv = document.createElement('div');
    rechercheDiv.classList.add('recherche');

    let rechercheTitleDiv = document.createElement('div');
    rechercheTitleDiv.classList.add('rechercheTitle');

    let col12Div = document.createElement('div');
    col12Div.classList.add('col-12');
    let titleH4 = document.createElement('h4');
    titleH4.classList.add('text-center');
    titleH4.textContent = 'Recherche';
    col12Div.appendChild(titleH4);
    rechercheTitleDiv.appendChild(col12Div);
    rechercheDiv.appendChild(rechercheTitleDiv);

    let buttonRechercheDiv = document.createElement('div');
    buttonRechercheDiv.classList.add('buttonRecherche', 'text-center');

    let buttonMusique = document.createElement('button');
    buttonMusique.setAttribute('type', 'button');
    buttonMusique.classList.add('btn', 'btn-danger', 'ecart');
    buttonMusique.setAttribute('id', 'buttonRecherche');
    buttonMusique.textContent = 'Musique';

    buttonMusique.addEventListener('click', function () {
        let recherche = document.getElementById('rechercheText').value;
        // console.log(recherche);
        getSearchMusic(recherche);
    });

    let buttonAlbum = document.createElement('button');
    buttonAlbum.setAttribute('type', 'button');
    buttonAlbum.classList.add('btn', 'btn-danger', 'ecart');
    buttonAlbum.setAttribute('id', 'buttonRecherche');
    buttonAlbum.textContent = 'Album';

    buttonAlbum.addEventListener('click', function () {
        let recherche = document.getElementById('rechercheText').value;
        getSearchAlbum(recherche);
    });

    let buttonArtiste = document.createElement('button');
    buttonArtiste.setAttribute('type', 'button');
    buttonArtiste.classList.add('btn', 'btn-danger', 'ecart');
    buttonArtiste.setAttribute('id', 'buttonRecherche');
    buttonArtiste.textContent = 'Artiste';

    buttonArtiste.addEventListener('click', function () {
        let recherche = document.getElementById('rechercheText').value;
        getSearchArtist(recherche);
    });

    buttonRechercheDiv.appendChild(buttonMusique);
    buttonRechercheDiv.appendChild(buttonAlbum);
    buttonRechercheDiv.appendChild(buttonArtiste);
    rechercheDiv.appendChild(buttonRechercheDiv);

    if(data.length == 0){
        let col12Div = document.createElement('div');
        col12Div.classList.add('col-12','marginTop');
        let titleH4 = document.createElement('h4');
        titleH4.classList.add('text-center');
        titleH4.textContent = 'Aucun résultat';
        col12Div.appendChild(titleH4);
        rechercheDiv.appendChild(col12Div);
        container.appendChild(rechercheDiv);
    }else{
        for (let i = 0; i < data.length; i++) {
            let albumDiv = document.createElement('div');
            albumDiv.classList.add('album');
            
            let imgAlbum = document.createElement('img');
            imgAlbum.setAttribute('src', data[i].album_chemin);
            imgAlbum.setAttribute('alt', 'album');
            imgAlbum.classList.add('img-fluid');
            imgAlbum.style.maxWidth = '150px';
            imgAlbum.style.maxHeight = '150px';
            albumDiv.appendChild(imgAlbum);

            imgAlbum.addEventListener('click', function () {
                let album_id = data[i].id_album;
                getOneAlbum(album_id);
            });
            
            let h1Title = document.createElement('h1');
            h1Title.textContent = data[i].album_title;
            albumDiv.appendChild(h1Title);

            h1Title.addEventListener('click', function () {
                let album_id = data[i].id_album;
                getOneAlbum(album_id);
            });
            
            let h3Name = document.createElement('h3');
            let name = '';
            if (data[i].artiste_lastname != null) {
                name = data[i].artiste_name + ' ' + data[i].artiste_lastname;
            } else {
                name = data[i].artiste_name;
            }
            h3Name.textContent = name;
            albumDiv.appendChild(h3Name);
            
            h3Name.addEventListener('click', function () {
                let artiste_id = data[i].artiste_id;
                getOneArtist(artiste_id);
            });
            
            let h3Creation = document.createElement('h3');
            h3Creation.textContent = data[i].album_creation;
            albumDiv.appendChild(h3Creation);
            
            let h3Style = document.createElement('h3');
            h3Style.textContent = 'Style : ' + data[i].album_style;
            albumDiv.appendChild(h3Style);
            
            rechercheDiv.appendChild(albumDiv);            
        }
        container.appendChild(rechercheDiv);
    }
}