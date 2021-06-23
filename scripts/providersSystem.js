const contactsInput = document.getElementById('contactsInput');
const contactsType = document.getElementById('contactsType');
const tbodyElement = document.getElementById('tbody');

let contactsData = [];
let providerData = []

function generateTable(contactsArray) {
    tbodyElement.innerHTML = '';

    for (let i = 0; i < contactsArray.length; i++) {
        const trElement = document.createElement('tr');
        const thContactElement = document.createElement('th');
        const thTypeElement = document.createElement('th');
        const thActionsElement = document.createElement('th');

        tbodyElement.appendChild(trElement);

        thContactElement.innerHTML = contactsArray[i].contact;
        thTypeElement.innerHTML = contactsArray[i].type;

        trElement.appendChild(thContactElement)
        trElement.appendChild(thTypeElement)
    }
}


document.getElementById('createProviderContact').addEventListener('click', (e) => {
    e.preventDefault();

    if (contactsInput.value.replace(/ /g, '').length > 0) {
        const oneContactData = {
            contact: contactsInput.value,
            type: contactsType.value
        }
        contactsData.push(oneContactData);
        generateTable(contactsData)
    } else {
        console.log(contactsInput.value.replace(/ /g, '').length)
    }

})

document.getElementById('createProviderBtn').addEventListener('click', (e) => {
    e.preventDefault();

    if (contactsData.length != 0) {
        const providerData = {
            name: nameProvider.value,
            obs: obsProvider.value,

            contacts: contactsData
        }

        var xhttp = new XMLHttpRequest();
        xhttp.open("POST", "../actions/createProvider.php", true);
        xhttp.setRequestHeader("Content-Type", "application/json");
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);
                window.location.href = "/pages/home.php";
            }
        };

        xhttp.send(JSON.stringify(providerData));
    }
})