function onSignIn(googleUser) {
    var idToken = googleUser.getAuthResponse().id_token;

    sendToBack(idToken);
}

function sendToBack(idToken){
    var request = new XMLHttpRequest();
    request.open('POST', 'app/php/google/verifyIntegrity.php'); 
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.send('idToken=' + idToken);

    request.onreadystatechange = () => {
        if(request.readyState == 4 && request.status == 200) {
            window.location = request.responseText;
        }
    };
}

function onFailure(error) {
    console.log(error);
}

function renderButton() {
    gapi.signin2.render('my-signin2', {
        'scope': 'profile email',
        'width': 300,
        'height': 50,
        'longtitle': true,
        'theme': 'light',
        'onsuccess': onSignIn,
        'onfailure': onFailure
    });
}