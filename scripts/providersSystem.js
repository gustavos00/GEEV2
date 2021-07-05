const contactsInput = document.getElementById('contactsInput');
const contactsType = document.getElementById('contactsType');
const tbodyElement = document.getElementById('tbody');
const form = document.getElementById('form');

let contactsData = [];
let providerData = []
let actionFile = 'createProvider';
let id = 0;

if (document.getElementById('id')) {
    id = document.getElementById('id').value

    const trElements = document.querySelectorAll('table tbody tr');
    
    trElements.forEach(element => {
        const contact = element.cells[0].innerHTML;
        const contactType = element.cells[1].innerHTML;

        const oneContactData = {
            contact: contact,
            type: contactType
        }
        contactsData.push(oneContactData);
    });

    actionFile = 'updateProvider';
}

function generateTable(contactsArray) {
    tbodyElement.innerHTML = '';

    for (let i = 0; i < contactsArray.length; i++) {
        const trElement = document.createElement('tr');
        const thContactElement = document.createElement('th');
        const thTypeElement = document.createElement('th');
        const thActionsElement = document.createElement('th');
        const trashcanImg = document.createElement('div');

        thActionsElement.classList.add('thActionsTable');
        trashcanImg.classList.add('trashcanImage')
        thContactElement.innerHTML = contactsArray[i].contact;
        thTypeElement.innerHTML = contactsArray[i].type;

        tbodyElement.appendChild(trElement);
        thActionsElement.appendChild(trashcanImg);
        trElement.appendChild(thContactElement)
        trElement.appendChild(thTypeElement)
        trElement.appendChild(thActionsElement)
    }
}

function request(providerData) {
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "../actions/" + actionFile + ".php", true);
    xhttp.setRequestHeader("Content-Type", "application/json");
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
        }
    };

    xhttp.send(JSON.stringify(providerData));
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

    if (contactsData.length != 0 || confirm("Tem a certeza que deseja criar um fornecedor sem contactos?")) {
        const providerData = {
            name: nameProvider.value,
            obs: obsProvider.value,
            id: id,

            contacts: contactsData
        }
        console.log(providerData);
        request(providerData);
    }
})