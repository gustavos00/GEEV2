const form = document.getElementById('form');
const cookieName = form.dataset.cookiename;
const cookieData = JSON.parse(getCookie(cookieName));

function handleSubmit() {
    const data = new FormData(form);
    const value = JSON.stringify(Object.fromEntries(data.entries()));

    writeCookie(cookieName, value, 1);
    destroyPHPsession()
}

function writeCookie(key, value, days) {
    let date = new Date();

    days = days || 365;
    date.setTime(+ date + (days * 86400000)); //24 * 60 * 60 * 1000

    window.document.cookie = key + "=" + value + "; expires=" + date.toGMTString() + "; path=/";

    return value;
};

function getCookie(name) {
    let pattern = RegExp(name + "=.[^;]*")
    let matched = document.cookie.match(pattern)
    if (matched) {
        let cookie = matched[0].split('=')
        return cookie[1]
    }
    return false
}

function destroyPHPsession() {
    var xhttp = new XMLHttpRequest();
    
    xhttp.open("POST", "../actions/system/destroySession.php", true);
    xhttp.setRequestHeader("Content-Type", "application/json");
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
        }
    };

    xhttp.send();
    location.reload();
}

for (let i in cookieData) {
    if (cookieData[i] !== "" && cookieData !== undefined) {
        document.getElementsByName(i)[0].value = cookieData[i]
    }
}

window.addEventListener("beforeunload", handleSubmit);