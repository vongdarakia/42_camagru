function submitSignUp() {
    let pass1 = document.getElementById('pass1').value;
    let pass2 = document.getElementById('pass2').value;

    if (pass1 == pass2) {
        document.querySelector('.hidden-submit').click();
    } else {
        
    }
}

function submit() {
    document.querySelector('.hidden-submit').click();
}