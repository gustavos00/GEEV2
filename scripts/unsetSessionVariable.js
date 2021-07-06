const buttons = document.getElementsByClassName("unsetSessionVariable");

for (let button of buttons) {
    button.addEventListener('click', (e) => {
        e.preventDefault();
        const variable = e.target.dataset.sessionName;

        var data = { variable: variable };

        var xhttp = new XMLHttpRequest();
        xhttp.open("POST", "../actions/system/unsetSessionVariable.php", true);
        xhttp.setRequestHeader("Content-Type", "application/json");
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);
            }
        };

        xhttp.send(JSON.stringify(data));
        location.reload();
    })
}

