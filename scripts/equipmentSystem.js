//CREATE TYPE, BRAND OR STATE MODAL
const equipmentsActionButton = document.getElementsByClassName('equipmentsActionButton');


function request(data) {
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "../actions/createStateCategoryOrBrand.php", true);
    xhttp.setRequestHeader("Content-Type", "application/json");
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText)
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

                    request(data);
                } else if (action === 'create') {
                    const inputs = modal.querySelectorAll('input[type=text]');
                    const content = inputs[0].value;

                    if (inputs.length == 1 && !content.replace(/ /g, '') == "") {
                        data = {
                            content: content.replace(/ /g, ''),
                            action: action,
                            who: who
                        };
                    } else if (inputs.length == 2) {
                        const categoryCode = inputs[1].value;

                        if (categoryCode != null && !categoryCode.replace(/ /g, '') == "" && !content.replace(/ /g, '') == "") {
                            data = {
                                content: content.replace(/ /g, ''),
                                code: categoryCode.replace(/ /g, ''),
                                action: action,
                                who: who
                            };
                        }
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
