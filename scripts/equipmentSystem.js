//CREATE TYPE, BRAND OR STATE MODAL
const equipmentsActionButton = document.getElementsByClassName('equipmentsActionButton');
const createActionBtns = document.getElementsByClassName('createAction');

for (let i = 0; i < equipmentsActionButton.length; i++) {
    const element = equipmentsActionButton[i];

    element.addEventListener('click', (e) => {
        e.preventDefault();

        const modalId = element.dataset.modalid; //Get modal id 
        const modal = document.getElementById(modalId); //get modal element
        const who = modal.dataset.who;

        const input = modal.querySelectorAll('input[type=text]');

        const actionButtonId = modal.dataset.actionbuttonid; //get modal action btn id
        const actionButton = document.getElementById(actionButtonId); //get modal action btn element

        modalFilter.style.display = 'flex'; //Modal Filter
        modal.style.display = 'flex'; //modal content

        actionButton.addEventListener('click', (e) => {
            e.preventDefault();
            if (input.length == 1 && !input[0].value == "" || !input[0].value == undefined) {
                var data = {
                    content: input[0].value,
                    who: who
                };
                console.log(data);
            } else if (input.length == 2 && !input[0].value == "" || !input[0].value == undefined && !input[1].value == "" || !input[1].value == undefined) {
                var data = {
                    content: input[0].value,
                    code: input[1].value,
                    who: who
                };
                console.log(data);
            } else {
                alert('Ocorreu um erro, tente novamente.');
            }

            console.log(' Request x');
            var xhttp = new XMLHttpRequest();
            xhttp.open("POST", "../actions/createStateCategoryOrBrand.php", true);
            xhttp.setRequestHeader("Content-Type", "application/json");
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);
                }

            };
            xhttp.send(JSON.stringify(data));

        })
    })
}

window.addEventListener("click", (e) => {
    const modalsContent = modalFilter.querySelectorAll('.modalContent');

    if (e.target == modalFilter) {
        for (let modal of modalsContent) {
            modal.style.display = 'none';
        }

        modalFilter.style.display = "none";
    }
});
