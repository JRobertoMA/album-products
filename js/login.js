if (localStorage.getItem("uuid") == undefined) {
    localStorage.setItem("uuid", uuidv4());
}

function uuidv4() {
    return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
        (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
    );
}

function login() {
    var formElement = document.getElementById("form-login");
    var formdata = new FormData(formElement);
    formdata.append("uuid", localStorage.getItem("uuid"));
    axios.post("modules/user/login.php", formdata, {headers: { "Content-Type": "multipart/form-data" },}).then((response) => {
        if (response.data.status == "ok") {
            localStorage.setItem('jwt', response.data.jwt);
            location.href = "index.html";
        } else {
            alert(response.data.answer);
        }
    },(error) => {
        console.log(error);
    });
}