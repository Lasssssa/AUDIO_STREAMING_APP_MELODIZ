
// FONCTION DE REQUETE AJAX
export function ajaxRequest(type, url, callback, data = null)
{
  let xhr;

  // Create XML HTTP request.
  xhr = new XMLHttpRequest();
  if (type == 'GET' && data != null)
    url += '?' + data;
  xhr.open(type, url);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  // Add the onload function.
  xhr.onload = () =>
  {
    switch (xhr.status)
    {
      case 200:
      case 201:
        // console.log(xhr.responseText);
        callback(JSON.parse(xhr.responseText));
        break;
      default:
        httpErrors(xhr.status);
    }
  };

  // Send XML HTTP request.
  xhr.send(data);
}

//FONCTION D'ERREUR
export function httpErrors(errorCode){
    let error =  "";
    switch(errorCode){
        case 404: error = 'Page non trouvée';
        break;
        default : error = "Erreur inconnue";
    }
    document.getElementById('errors').innerHTML +=  '<div class="alert alert-danger" role="alert">'+error+'</div>';
}

//FONCTION DE CLEAR EN CAS D'ERROR
function clearErrors(){
    document.getElementById('errors').innerHTML = "";
}

setTimeout(clearErrors,5000);

  
