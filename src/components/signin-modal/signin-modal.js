document.querySelector("#signin-form").addEventListener("submit", function (event) {
    event.preventDefault()
    signin()
    event.target.reset()
});

function signin() {

    const formDataUser = new FormData()
    formDataUser.append('name', document.querySelector("#signin-input-name").value)
    formDataUser.append('surname', document.querySelector("#signin-input-surname").value)
    formDataUser.append('username', document.querySelector("#signin-input-username").value)
    formDataUser.append('email', document.querySelector("#signin-input-email").value)
    formDataUser.append('password', document.querySelector("#signin-input-password").value)

    axios.post('./api/signin.php', formDataUser).then(responseSignin => {
        if (responseSignin.data["signinEseguito"]) {
            document.querySelector("#signin-form > p").innerText = "Registrazione eseguita con successo!"
            setTimeout(() => document.location.href = "", 2000);
        } else {
            document.querySelector("#signin-form > p").innerText = responseSignin.data["erroreSignin"]
        }
    });
}
