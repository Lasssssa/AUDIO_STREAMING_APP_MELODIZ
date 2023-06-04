
import { ajaxRequest } from "./ajax.js";


export function displayAccount(data){
    let container = document.getElementById('container');

    container.innerHTML = "";

    let infoPersoDiv = document.createElement('div');
    infoPersoDiv.id = 'infoPerso';

    let titlePersoDiv = document.createElement('div');
    titlePersoDiv.classList.add('titlePerso');

    let mainAddingDiv = document.createElement('div');
    mainAddingDiv.id = 'mainAdding';

    let formAddingDiv = document.createElement('div');
    formAddingDiv.id = 'formAdding';

    let h2Title = document.createElement('h2');
    h2Title.classList.add('center');
    h2Title.textContent = 'INFORMATIONS PERSONNELLES';

    let ppV2Div = document.createElement('div');
    ppV2Div.classList.add('ppV2');

    let ppImg = document.createElement('img');
    ppImg.src = 'photo_profil/profil_defaut.png';
    ppImg.classList.add('pp');
    ppImg.alt = 'photo de profil';

    ppV2Div.appendChild(ppImg);

    let importDiv = document.createElement('div');
    importDiv.classList.add('import');

    let h5Import = document.createElement('h5');
    h5Import.textContent = 'Importer votre photo de profil';

    let formImport = document.createElement('form');
    formImport.action = 'perso.php';
    formImport.method = 'post';
    formImport.enctype = 'multipart/form-data';

    let mb3Div = document.createElement('div');
    mb3Div.classList.add('mb-3');

    let fileInput = document.createElement('input');
    fileInput.classList.add('form-control');
    fileInput.type = 'file';
    fileInput.name = 'photo_profil';
    fileInput.id = 'fileToUpload';

    let submitButton = document.createElement('button');
    submitButton.type = 'submit';
    submitButton.classList.add('btn', 'btn-danger');
    submitButton.name = 'submit_photo';
    submitButton.textContent = 'Importer';

    mb3Div.appendChild(fileInput);
    formImport.appendChild(mb3Div);
    formImport.appendChild(submitButton);
    importDiv.appendChild(h5Import);
    importDiv.appendChild(formImport);

    let rowDiv1 = document.createElement('div');
    rowDiv1.classList.add('row');

    let col1Div = document.createElement('div');
    col1Div.classList.add('col');

    let h4Prenom = document.createElement('h4');
    h4Prenom.textContent = 'Prénom';

    let inputPrenom = document.createElement('input');
    inputPrenom.classList.add('form-control');
    inputPrenom.id = 'nameForm';
    inputPrenom.name = 'prenom';
    inputPrenom.type = 'text';
    inputPrenom.value = 'Théo';

    col1Div.appendChild(h4Prenom);
    col1Div.appendChild(inputPrenom);

    let col2Div = document.createElement('div');
    col2Div.classList.add('col');

    let h4Nom = document.createElement('h4');
    h4Nom.textContent = 'Nom';

    let inputNom = document.createElement('input');
    inputNom.classList.add('form-control');
    inputNom.id = 'lastnameForm';
    inputNom.name = 'nom';
    inputNom.type = 'text';
    inputNom.value = 'Porodo';

    col2Div.appendChild(h4Nom);
    col2Div.appendChild(inputNom);

    rowDiv1.appendChild(col1Div);
    rowDiv1.appendChild(col2Div);

    let rowDiv2 = document.createElement('div');
    rowDiv2.classList.add('row');

    let col3Div = document.createElement('div');
    col3Div.classList.add('col');

    let h4DateNaissance = document.createElement('h4');
    h4DateNaissance.textContent = 'Date de naissance';

    let inputDateNaissance = document.createElement('input');
    inputDateNaissance.classList.add('form-control');
    inputDateNaissance.id = 'birthdateForm';
    inputDateNaissance.name = 'date_naissance';
    inputDateNaissance.type = 'date';
    inputDateNaissance.value = '1998-05-02';

    col3Div.appendChild(h4DateNaissance);
    col3Div.appendChild(inputDateNaissance);

    let col4Div = document.createElement('div');
    col4Div.classList.add('col');

    let h4Age = document.createElement('h4');
    h4Age.textContent = 'Age';

    let inputAge = document.createElement('input');
    inputAge.classList.add('form-control');
    inputAge.type = 'text';
    inputAge.value = '45';
    inputAge.setAttribute('aria-label', 'Disabled input example');
    inputAge.disabled = true;

    col4Div.appendChild(h4Age);
    col4Div.appendChild(inputAge);

    rowDiv2.appendChild(col3Div);
    rowDiv2.appendChild(col4Div);

    let rowDiv3 = document.createElement('div');
    rowDiv3.classList.add('row');

    let col5Div = document.createElement('div');
    col5Div.classList.add('col');

    let h4Telephone = document.createElement('h4');
    h4Telephone.textContent = 'Numéro de téléphone';

    let inputTelephone = document.createElement('input');
    inputTelephone.classList.add('form-control');
    inputTelephone.id = 'telephoneForm';
    inputTelephone.name = 'telephone';
    inputTelephone.type = 'text';
    inputTelephone.value = '0781908419';

    col5Div.appendChild(h4Telephone);
    col5Div.appendChild(inputTelephone);

    rowDiv3.appendChild(col5Div);

    let formGroup1 = document.createElement('div');
    formGroup1.classList.add('form-group');

    let h4Email = document.createElement('h4');
    h4Email.textContent = 'Email';

    let inputEmail = document.createElement('input');
    inputEmail.classList.add('form-control');
    inputEmail.id = 'emailForm';
    inputEmail.name = 'email';
    inputEmail.type = 'text';
    inputEmail.value = 'theo@gmail.com';

    formGroup1.appendChild(h4Email);
    formGroup1.appendChild(inputEmail);

    let formGroup2 = document.createElement('div');
    formGroup2.classList.add('form-group');

    let h4MotDePasse = document.createElement('h4');
    h4MotDePasse.textContent = 'Mot de passe';

    let inputMotDePasse = document.createElement('input');
    inputMotDePasse.classList.add('form-control');
    inputMotDePasse.type = 'text';
    inputMotDePasse.value = '********';
    inputMotDePasse.setAttribute('aria-label', 'Disabled input example');
    inputMotDePasse.disabled = true;

    formGroup2.appendChild(h4MotDePasse);
    formGroup2.appendChild(inputMotDePasse);

    let centerDiv = document.createElement('div');
    centerDiv.classList.add('center');

    let modifyAccountButton = document.createElement('button');
    modifyAccountButton.classList.add('btn', 'btn-danger');
    modifyAccountButton.id = 'modifyAccount';
    modifyAccountButton.name = 'submit_account';
    modifyAccountButton.textContent = 'Modifier';

    centerDiv.appendChild(modifyAccountButton);

    let brElement = document.createElement('br');

    let accordionDiv = document.createElement('div');
    accordionDiv.classList.add('accordion');
    accordionDiv.id = 'accordionPanelsStayOpenExample';

    let accordionItemDiv = document.createElement('div');
    accordionItemDiv.classList.add('accordion-item');

    let accordionHeader = document.createElement('h2');
    accordionHeader.classList.add('accordion-header');

    let accordionButton = document.createElement('button');
    accordionButton.classList.add('accordion-button', 'collapsed');
    accordionButton.type = 'button';
    accordionButton.setAttribute('data-bs-toggle', 'collapse');
    accordionButton.setAttribute('data-bs-target', '#panelsStayOpen-collapseOne');
    accordionButton.setAttribute('aria-expanded', 'false');
    accordionButton.setAttribute('aria-controls', 'panelsStayOpen-collapseOne');
    accordionButton.textContent = 'MODIFICATION DU MOT DE PASSE';

    accordionHeader.appendChild(accordionButton);
    accordionItemDiv.appendChild(accordionHeader);

    let accordionCollapseDiv = document.createElement('div');
    accordionCollapseDiv.id = 'panelsStayOpen-collapseOne';
    accordionCollapseDiv.classList.add('accordion-collapse', 'collapse');

    let accordionBodyDiv = document.createElement('div');
    accordionBodyDiv.classList.add('accordion-body');

    let formPassword = document.createElement('form');
    formPassword.action = 'infoEnseignant.php';
    formPassword.method = 'post';

    let formGroup3 = document.createElement('div');
    formGroup3.classList.add('form-group');

    let h4AncienMotDePasse = document.createElement('h4');
    h4AncienMotDePasse.textContent = 'Ancien mot de passe';

    let inputAncienMotDePasse = document.createElement('input');
    inputAncienMotDePasse.type = 'password';
    inputAncienMotDePasse.classList.add('form-control');
    inputAncienMotDePasse.id = 'password5';
    inputAncienMotDePasse.name = 'old_password';

    let h4NouveauMotDePasse = document.createElement('h4');
    h4NouveauMotDePasse.textContent = 'Nouveau mot de passe';

    let inputNouveauMotDePasse = document.createElement('input');
    inputNouveauMotDePasse.type = 'password';
    inputNouveauMotDePasse.classList.add('form-control');
    inputNouveauMotDePasse.id = 'password2';
    inputNouveauMotDePasse.name = 'new_password';

    formGroup3.appendChild(h4AncienMotDePasse);
    formGroup3.appendChild(inputAncienMotDePasse);
    formGroup3.appendChild(h4NouveauMotDePasse);
    formGroup3.appendChild(inputNouveauMotDePasse);

    let brElement2 = document.createElement('br');

    let submitPasswordButton = document.createElement('button');
    submitPasswordButton.type = 'submit';
    submitPasswordButton.classList.add('btn', 'btn-primary', 'coloredV2');
    submitPasswordButton.name = 'submit_password';
    submitPasswordButton.textContent = 'Modifier';

    formPassword.appendChild(formGroup3);
    formPassword.appendChild(brElement2);
    formPassword.appendChild(submitPasswordButton);

    accordionBodyDiv.appendChild(formPassword);
    accordionCollapseDiv.appendChild(accordionBodyDiv);

    accordionItemDiv.appendChild(accordionCollapseDiv);

    accordionDiv.appendChild(accordionItemDiv);

    formAddingDiv.appendChild(h2Title);
    formAddingDiv.appendChild(brElement);
    formAddingDiv.appendChild(ppV2Div);
    formAddingDiv.appendChild(brElement);
    formAddingDiv.appendChild(importDiv);
    formAddingDiv.appendChild(rowDiv1);
    formAddingDiv.appendChild(brElement);
    formAddingDiv.appendChild(rowDiv2);
    formAddingDiv.appendChild(brElement);
    formAddingDiv.appendChild(rowDiv3);
    formAddingDiv.appendChild(brElement);
    formAddingDiv.appendChild(formGroup1);
    formAddingDiv.appendChild(brElement);
    formAddingDiv.appendChild(formGroup2);
    formAddingDiv.appendChild(brElement);
    formAddingDiv.appendChild(centerDiv);
    formAddingDiv.appendChild(brElement);
    formAddingDiv.appendChild(accordionDiv);
    infoPersoDiv.appendChild(titlePersoDiv);
    titlePersoDiv.appendChild(mainAddingDiv);
    mainAddingDiv.appendChild(formAddingDiv);
    container.appendChild(infoPersoDiv);

    let buttonModify = document.getElementById('modifyAccount');

    buttonModify.addEventListener('click', function() {
        let data = '';
        let name = document.getElementById('nameForm').value;
        let lastname = document.getElementById('lastnameForm').value;
        let email = document.getElementById('emailForm').value;
        let id_user = document.getElementById('id_user').value;
        let birthdate = document.getElementById('birthdateForm').value;
        let telephone = document.getElementById('telephoneForm').value;
        data = 'request=modifyAccount&name='+name+'&lastname='+lastname+'&email='+email+'&idPerso='+id_user+'&birthdate='+birthdate+'&telephone='+telephone;
        console.log(data);
        ajaxRequest('PUT', 'php/request.php?'+data, displayModifyAccount);
    });

}


function displayModifyAccount(data){
    console.log(data);
}