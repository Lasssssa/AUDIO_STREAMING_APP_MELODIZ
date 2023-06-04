function togglePassword() {
    let x = document.getElementById("password");
    let y = document.getElementById("showPassword");
    if (y.checked) {
        x.type = "text";
    } else {
        x.type = "password";
    }
    
}
function togglePasswordRe(){
    let x = document.getElementById("password1");
    let y = document.getElementById("showPassword1");
    if (y.checked) {
        x.type = "text";
    } else {
        x.type = "password";
    }
}

function togglePasswordRe2(){
    let x = document.getElementById("password2");
    let y = document.getElementById("showPassword2");
    if (y.checked) {
        x.type = "text";
    } else {
        x.type = "password";
    }
}