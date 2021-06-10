//CREATE HIDDEN INPUT ON CREATE/UPDATE MALFUNCTION TO SET ON DATABASE ASSISTANCE ID
const actionButton = document.getElementById('actionButton');
const who = actionButton.dataset.who;
const mainForm = document.getElementById('form');
const assistance = document.getElementById('assistance');


actionButton.addEventListener('click', (e) => {
    e.preventDefault();

    document.getElementById('assistanceId').value = assistance.options[assistance.selectedIndex].dataset.id;
    mainForm.submit();
})