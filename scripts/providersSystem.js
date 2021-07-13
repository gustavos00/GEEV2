const softwareActionButton = document.getElementsByClassName('actionBtn');
const contactsInput = document.getElementById('contactsInput');
const contactsType = document.getElementById('contactsType');
const tbodyElement = document.getElementById('tbody');
const form = document.getElementById('form');

let contactsData = [];
let providerData = [];
let oldContactData = [];
let actionFile = 'createProvider';
let id = 0;

async function getAllProviderContacts(id) {
    fetch("../actions/getAllProviderContacts.php", {
        method: 'POST',
        body: JSON.stringify(id),
        headers: {
            'Content-type': 'application/json; charset=UTF-8'
        }
    })
        .then(response => response.json())
        .then(response => {
            for (let i = 0; i < response.length; i++) {
                const oneContactData = {
                    contact: response[i].contact,
                    type: response[i].contactType,
                }
                contactsData.push(oneContactData);
                oldContactData = contactsData;

                generateTable(contactsData)
            }
        })
        .catch(() => {
            console.log('error getting provider contacts');
        })
}

function generateTable(contactsArray) {
    tbodyElement.innerHTML = '';

    for (let i = 0; i < contactsArray.length; i++) {
        const trElement = document.createElement('tr');
        const thContactElement = document.createElement('th');
        const thTypeElement = document.createElement('th');
        const thActionsElement = document.createElement('th');

        trElement.setAttribute('data-index', i);
        thActionsElement.innerHTML = 'Apagar'
        thContactElement.innerHTML = contactsArray[i].contact;
        thTypeElement.innerHTML = contactsArray[i].type;

        tbodyElement.appendChild(trElement);
        trElement.appendChild(thContactElement)
        trElement.appendChild(thTypeElement)
        trElement.appendChild(thActionsElement)

        thActionsElement.addEventListener('click', (e) => {
            contactsArray.splice(thActionsElement.dataset.index, 1);
            generateTable(contactsArray);
        })
    }
}

function closeModal() {
    const modalsContent = modalFilter.querySelectorAll('.modalContent');

    for (let modal of modalsContent) {
        modal.style.display = 'none';
    }

    modalFilter.style.display = "none";
}

//General xhttp request 
function request(providerData) {
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "../actions/" + actionFile + ".php", true);
    xhttp.setRequestHeader("Content-Type", "application/json");
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            window.location.href = "/GEEV2/pages/home.php"
        }
    };

    xhttp.send(JSON.stringify(providerData));
}

if (document.getElementById('id')) {
    id = document.getElementById('id').value

    getAllProviderContacts(id)

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

//Create provider contact click listener
document.getElementById('createProviderContact').addEventListener('click', (e) => {
    e.preventDefault();

    if (!contactsInput.value.replace(/ /g, '') == "") {
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

//Submit form button click listener 
document.getElementById('createProviderBtn').addEventListener('click', (e) => {
    e.preventDefault();

    if (contactsData.length != 0 || confirm("Tem a certeza que deseja criar um fornecedor sem contactos?") && !nameProvider.value.replace(/ /g, '') == "") {
        let status = 'd';

        const providerData = {
            name: nameProvider.value,
            obs: obsProvider.value,
            id: id,
            status: status,

            contacts: contactsData
        }

        console.log(actionFile)
        request(providerData);
    }

})

//CREATE PROVIDER CONTACT TYPE

for (let i = 0; i < softwareActionButton.length; i++) {
    const element = softwareActionButton[i];

    element.addEventListener('click', (e) => {
        e.preventDefault();

        const modalId = element.dataset.modalid; //Get modal id 
        const modal = document.getElementById(modalId); //get modal element

        const createActionRadioId = modal.dataset.createid; //get create action radio element id
        const deleteActionRadioId = modal.dataset.deleteid; //get create action radio element id

        const actionBtnClass = modal.dataset.createbtnclass; //get create modal action btn class
        const actionBtn = document.getElementsByClassName(actionBtnClass); //get create modal action btn element

        const createAction = document.getElementById(createActionRadioId); //get create action radio element
        const deleteAction = document.getElementById(deleteActionRadioId); //get delete action radio element

        const createActionModalId = createAction.dataset.optionid; //get create action content id 
        const createActionModal = document.getElementById(createActionModalId);

        const deleteActionModalId = deleteAction.dataset.optionid; //get delete action content id
        const deleteActionModal = document.getElementById(deleteActionModalId);

        modalFilter.style.display = 'flex'; //Modal Filter
        modal.style.display = 'flex'; //modal content

        //RADIO BUTTONS

        createAction.addEventListener('click', (e) => {
            modal.style.height = '230px';
            deleteActionModal.style.display = 'none';
            createActionModal.style.display = 'flex';

        })

        deleteAction.addEventListener('click', (e) => {
            modal.style.height = '230px';
            createActionModal.style.display = 'none';
            deleteActionModal.style.display = 'flex';
        })

        // MODAL BUTTONS 

        for (let k = 0; k < actionBtn.length; k++) {
            let element = actionBtn[k];
            actionFile = 'createProviderContactType'

            element.addEventListener('click', (e) => {

                e.preventDefault();

                const action = element.dataset.action;
                let data = null;

                if (action === 'delete') {
                    const input = modal.querySelector('select');
                    const content = input.options[input.selectedIndex].text;

                    let data = {
                        content: content,
                        action: action,

                    };

                    request(data);
                } else if (action === 'create') {
                    const inputs = modal.querySelectorAll('input[type=text]');
                    const content = inputs[0].value;

                    if (!content.replace(/ /g, '') == "") {
                        data = {
                            content: content.replace(/ /g, ''),
                            action: action,

                        };
                    } else {
                        console.log(inputs.length)
                    }

                    request(data);
                } else {
                    alert('Verifique se nenhum campo está vazio.');
                }
            })
        }
    })
}

window.addEventListener("click", (e) => {
    if (e.target == modalFilter) {
        closeModal();
    }
});
