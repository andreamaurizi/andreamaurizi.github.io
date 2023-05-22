const menu = document.querySelector('#mobile-menu')
const menuLinks = document.querySelector('.navbar__menu')
const buttonSearch = document.getElementsByClassName('searchDiv');


menu.addEventListener('click',function(){
    menu.classList.toggle('is-active')
    menuLinks.classList.toggle('active') /*Toggle attiva/disattiva al click l'elemento 'active' da style.css */
})

menuLinks.addEventListener('click',function(){
    menu.classList.toggle('is-active') 
    menuLinks.classList.toggle('active') /*Toggle attiva/disattiva al click l'elemento 'active' da style.css */
})



function validaRegistrazione(){
    email = document.registrazione.email;
    password = document.registrazione.password;
    conferma_password = document.registrazione.conferma_password;

    if(validaEmail(email)){
        if(validaPassword(password, 8)){
            if(password.value != conferma_password.value){
                alert("La password deve coincidere!");
                return false;
            }
            else{
                return true;
            }
        }
    }
    return false;
}

function validaLogin(){
    var email = document.login.email;
    if(!validaEmail(email)){
        alert("Email in un formato non valido!");
        return false;
    }
    return true;
}


function validaEmail(email){
    var email_format = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if(email.value.match(email_format)){
        return true;
    }
    else{
        alert("L'email inserita non è valida!");
        email.focus();
        return false;
    }
}

function validaPassword(password, nc){

    var pass_len= password.value.length;
    if(pass_len < nc){
        alert("La password deve contenere almeno " + nc + " caratteri");
        password.focus();
        return false
    }
    var pass_format = /^([a-z0-9]+)$/i ;
    var pass_number = /\d/ ;
    var pass_char = /[a-z]/i ;
    if(!password.value.match(pass_format)){
        alert("La password deve essere alfanumerica!");
        password.focus();
        return false;
    }
    if(!password.value.match(pass_number)){
        alert("La password deve contenere almeno un numero!");
        password.focus();
        return false;
    }
    if(!password.value.match(pass_char)){
        alert("La password deve contenere almeno un carattere!");
        password.focus();
        return false;
    }

    return true;

}