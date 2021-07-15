//CREATE TYPE, BRAND OR STATE MODAL
const equipmentsActionButton = document.getElementsByClassName('equipmentsActionButton');
const tbodyElement = document.getElementById('tbody');
let softwaresData = []

async function getAllEquipmentSoftwares(id) {

    fetch("../actions/getAllEquipmentsSoftwares.php", {
        method: 'POST',
        body: JSON.stringify(id),
        headers: {
            'Content-type': 'application/json; charset=UTF-8'
        }
    })
        .then(response => response.json())
        .then(response => {
            for (let i = 0; i < response.length; i++) {
                const responseObj = JSON.parse(response[i]);

                const oneSoftwareData = {
                    name: responseObj.typeName + ' - ' + responseObj.version,
                    id: responseObj.id,
                }
                softwaresData.push(oneSoftwareData);
                generateTable(softwaresData);
            }
        })
}

function stateCategoryBrandRequest(data) {
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "../actions/createStateCategoryOrBrand.php", true);
    xhttp.setRequestHeader("Content-Type", "application/json");
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            closeModal();
        }

        if (this.readyState == 4 && this.status == 400) {
            const message = JSON.parse(this.responseText)
            alert(message.message)
        }

    };
    xhttp.send(JSON.stringify(data));

}

for (let i = 0; i < equipmentsActionButton.length; i++) {
    const element = equipmentsActionButton[i];

    element.addEventListener('click', (e) => {
        e.preventDefault();

        const modalId = element.dataset.modalid; //Get modal id 
        const modal = document.getElementById(modalId); //get modal element
        const createActionRadioId = modal.dataset.createid; //get create action radio element id
        const deleteActionRadioId = modal.dataset.deleteid; //get delete action radio element id
        const who = modal.dataset.who.toLowerCase();

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
            deleteActionModal.style.display = 'none';
            createActionModal.style.display = 'flex';

            if (who == 'category') {
                modal.style.height = '270px';
            } else {
                modal.style.height = '230px';
            }
        })

        deleteAction.addEventListener('click', (e) => {
            createActionModal.style.display = 'none';
            modal.style.height = '230px';
            deleteActionModal.style.display = 'flex';
        })

        // MODAL BUTTONS 

        for (let k = 0; k < actionBtn.length; k++) {
            let element = actionBtn[k];

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
                        who: who
                    };

                    stateCategoryBrandRequest(data);
                } else if (action === 'create') {
                    const inputs = modal.querySelectorAll('input[type=text]');
                    const content = inputs[0].value;

                    if (inputs.length == 1 && !content.replace(/ /g, '') == "") {
                        data = {
                            content: content.replace(/ /g, ''),
                            action: action,
                            who: who
                        };

                        stateCategoryBrandRequest(data);
                        closeModal()
                    } else if (inputs.length == 2) {
                        const categoryCode = inputs[1].value;

                        if (categoryCode != null && !categoryCode.replace(/ /g, '') == "" && !content.replace(/ /g, '') == "") {
                            data = {
                                content: content.replace(/ /g, ''),
                                code: categoryCode.replace(/ /g, ''),
                                action: action,
                                who: who
                            };

                            stateCategoryBrandRequest(data);
                            closeModal()
                        }
                    } else {
                        console.log(inputs.length)
                    }

                } else {
                    alert('Verifique se nenhum campo está vazio.');
                }
            })
        }
    })
}

function closeModal() {
    const modalsContent = modalFilter.querySelectorAll('.modalContent');

    for (let modal of modalsContent) {
        modal.style.display = 'none';
    }

    modalFilter.style.display = "none";
}

window.addEventListener("click", (e) => {
    if (e.target == modalFilter) {
        closeModal();
    }
});


//TABLE SYSTEM CREATE / UPDATE EQUIPMENT
if (document.getElementById('id')) {
    actionFile = 'updateEquipment'
    getAllEquipmentSoftwares(document.getElementById('id').value)

} else {
    actionFile = 'createEquipment'
}

//General xhttp request 
function request(data) {
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "../actions/" + actionFile + ".php", true);
    xhttp.setRequestHeader("Content-Type", "application/json");
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            window.location.href = 'home.php';
        }
        if (this.readyState == 4 && this.status == 400) {
            alert(this.response);
        }
    };

    xhttp.send(JSON.stringify(data));
}

function generateTable(softwareData) {
    tbodyElement.innerHTML = '';

    for (let i = 0; i < softwareData.length; i++) {
        const trElement = document.createElement('tr');
        const thSoftware = document.createElement('th');
        const thActionsElement = document.createElement('th');

        trElement.setAttribute('data-index', i);
        thActionsElement.innerHTML = 'Apagar'
        thSoftware.innerHTML = softwareData[i].name;

        tbodyElement.appendChild(trElement);
        trElement.appendChild(thSoftware)
        trElement.appendChild(thActionsElement)

        thActionsElement.addEventListener('click', (e) => {
            softwareData.splice(thActionsElement.dataset.index, 1);
            generateTable(softwareData);
        })
    }
}

const addSoftwareBtn = document.getElementById('addSoftwareBtn');
addSoftwareBtn.addEventListener('click', (e) => {
    e.preventDefault();

    const softwareSelect = document.getElementById('softwares')
    const selectedSoftware = softwareSelect.options[softwareSelect.selectedIndex];

    if (!selectedSoftware.hasAttribute('disabled')) {
        const value = {
            name: selectedSoftware.text,
            id: selectedSoftware.dataset.id
        }
        softwaresData.push(value);
        generateTable(softwaresData);

    } else {
        alert('O opção selecionada não é valida.')
    }
})

const submitFormBtn = document.getElementById('submitFormBtn')
submitFormBtn.addEventListener('click', (e) => {
    e.preventDefault();
    const provider = document.getElementById('provider')
    const brand = document.getElementById('brand');
    const state = document.getElementById('state');
    const category = document.getElementById('category');
    const status = 'd'; 
    let id = null;

    if(document.getElementById('id')) {
        id=document.getElementById('id').value;
    }

    const equipmentData = {
        id,
        internalCode: document.getElementById('internalCode').value,
        brand: brand.options[brand.selectedIndex].text,
        state: state.options[state.selectedIndex].text,
        category: category.options[category.selectedIndex].text,
        model: document.getElementById('model').value,
        serieNumber: document.getElementById('serieNumber').value,

        features: document.getElementById('features').value,
        obs: document.getElementById('obs').value,
        acquisitionDate: document.getElementById('acquisitionDate').value,
        patrimonialCode: document.getElementById('patrimonialCode').value,

        user: document.getElementById('user').value,
        location: document.getElementById('location').value,
        userDate: document.getElementById('userDate').value,

        lanPort: document.getElementById('lanPort').value,
        activeEquipment: document.getElementById('activeEquipment').value,
        ipAdress: document.getElementById('ipAdress').value,

        provider: provider.options[provider.selectedIndex].text,
        softwares: softwaresData,

        status: status,
    }

    request(equipmentData);
})