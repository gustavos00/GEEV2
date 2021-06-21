const contactsInput = document.getElementById('contactsInput');
const contactsType = document.getElementById('contactsType');
const tbodyElement = document.getElementById('tbody');

const contacts = [];

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
        const contactsData = {
            contact: contactsInput.value,
            type: contactsType.value
        }
        contacts.push(contactsData);
        generateTable(contacts)
    } else {
        console.log(contactsInput.value.replace(/ /g, '').length)
    }

})