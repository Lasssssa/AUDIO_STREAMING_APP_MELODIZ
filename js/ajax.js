export function ajaxRequest(type, url, callback, data = null){
    let xhr;
    // console.log(data);
    // Create XML HTTP request.
    xhr = new XMLHttpRequest();
    xhr.open(type, url);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    // Add response handler.
    xhr.onload = () =>
    {
      switch (xhr.status){
        case 200: 
          let resp = JSON.parse(xhr.responseText);
          // console.log(resp);
          callback(resp);
          break;
        case 201:
          // console.log(url);
        default:
          // console.log(xhr.status);
          httpErrors(xhr.status);
      }
    };
    // Send XML HTTP request.
    xhr.send(data);
  }

export function httpErrors(errorCode){
    let error =  "";
    switch(errorCode){
        case 404: error = 'Page non trouvée';
        break;
        default : error = "Erreur inconnue";
    }
    document.getElementById('errors').innerHTML +=  '<div class="alert alert-danger" role="alert">'+error+'</div>';
}

function clearErrors(){
    document.getElementById('errors').innerHTML = "";
}

setTimeout(clearErrors,5000);

  
