//CREATE TYPE, BRAND OR STATE MODAL
const softwareActionButton = document.getElementsByClassName('softwareActionButton');

function request(data) {
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "../actions/createSoftwareType.php", true);
    xhttp.setRequestHeader("Content-Type", "application/json");
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText)
            location.reload();
        }

        if (this.readyState == 4 && this.status == 400) {
            alert('Já existe esse tipo criado.')
        }

    };
    xhttp.send(JSON.stringify(data));

}

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
