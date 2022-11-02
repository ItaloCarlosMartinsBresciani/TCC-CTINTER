function onSignIn(googleUser) {
    var id_token = googleUser.getAuthResponse().id_token;

    sendToBack(id_token)
}

function sendToBack(id_token) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'php/verifyIntegrity.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.send('idtoken=' + id_token);

    window.location.href = 'http://localhost/estagio-UNESP/Project/google_login/home.php'
}

function onFailure(error) {
    console.log(error); 
}

function renderButton() {
    gapi.signin2.render('my-signin2', {
        'scope': 'profile email',
        'width': 240,
        'height': 50,
        'longtitle': true,
        'theme': 'dark',
        'onsuccess': onSignIn,
        'onfailure': onFailure
    });
}
