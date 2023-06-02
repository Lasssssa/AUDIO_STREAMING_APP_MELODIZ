function togglePassword() {
    let x = document.getElementById("password");
    let y = document.getElementById("showPassword");
    if (y.checked) {
        x.type = "text";
    } else {
        x.type = "password";
    }
    
}
