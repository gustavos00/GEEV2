const menuBtn = document.querySelector('.sidebarBtnContainer');
const sidebar = document.querySelector('.sidebar');
const actionsBtn = document.getElementsByClassName('actionButton');
const dropdownContent = document.getElementsByClassName('dropdownContent');

let menuOpen = false;
let dropdownOpen = false;

//Open sidebar area
function openSidebar() {
  menuBtn.classList.add('open');
  sidebar.style.width = "20vw";
  sidebar.style.maxWidth = "270px";
  sidebar.style.minWidth = "255px";

  for (let i = 0; i < actionsBtn.length; i++) {
    actionsBtn[i].style.display = 'flex';

    setTimeout(() => {
      actionsBtn[i].style.opacity = 1;
    }, 60)
  }

  menuOpen = true;
}

//Close Sidebar area
function closeSidebar() {
  menuBtn.classList.remove('open');
  sidebar.style.width = "5vw";
  sidebar.style.maxWidth = "80px";
  sidebar.style.minWidth = "65px";

  for (let i = 0; i < actionsBtn.length; i++) {
    setTimeout(() => {
      actionsBtn[i].style.opacity = 0;
    }, 30)

    actionsBtn[i].style.display = 'none';
  }
  closeAllDropdowns();
  menuOpen = false;
}

//Hide all dropdowns
function closeAllDropdowns() {
  for (let k = 0; k < dropdownContent.length; k++) {
    dropdownContent[k].style.opacity = 0;
    actionsBtn[k].style.setProperty("opacity", "0.5", "important")

    setTimeout(() => {
      dropdownContent[k].style.display = 'none'
    }, 30)
  }
}

//Show dropdown
function activeDropdown(dropdown, button) {
  dropdown.style.display = 'flex';
  button.style.setProperty("opacity", "1", "important")

  dropdown.style.opacity = 0;
  setTimeout(() => {
    dropdown.style.opacity = 1;
  }, 30)
}

//Hide dropdown
function desactiveDropdown(dropdown, button) {
  closeAllDropdowns();
  dropdown.style.opacity = 0;
  button.style.setProperty("opacity", ".5", "important")

  setTimeout(() => {
    dropdown.style.display = 'none'
  }, 300)
}

//onClick eventListener on sidebar buttons
menuBtn.addEventListener('click', () => {
  if (!menuOpen) {
    openSidebar();
  } else {
    closeSidebar();
  }
});

for (let i = 0; i < actionsBtn.length; i++) {
  actionsBtn[i].addEventListener('click', () => {
    const target = actionsBtn[i].innerText.toLowerCase();
    const arrow = actionsBtn[i].getElementsByTagName('i')[1];
    const dropdown = document.querySelector(`[data-dropdown='${target}']`);

    if (!dropdownOpen) {
      activeDropdown(dropdown, actionsBtn[i]);
      dropdownOpen = true;
    } else {
      desactiveDropdown(dropdown, actionsBtn[i]);
      dropdownOpen = false;
    }

  })
}

// DARK MODE SWITCH SYSTEM

const currentTheme = localStorage.getItem('theme') ? localStorage.getItem('theme') : null;
const toggleSwitch = document.querySelector('.darkmodeSwitchContent input[type="checkbox"]');

if (currentTheme) {
  document.documentElement.setAttribute('data-theme', currentTheme);

  if (currentTheme === 'dark') {
    toggleSwitch.checked = true;
  }
}

function switchTheme(e) {
  if (e.target.checked) {
    document.documentElement.setAttribute('data-theme', 'dark');
    localStorage.setItem('theme', 'dark'); //add this
  }
  else {
    document.documentElement.setAttribute('data-theme', 'light');
    localStorage.setItem('theme', 'light'); //add this
  }
}

toggleSwitch.addEventListener('change', switchTheme, false);

//MODALS 
const modalFilter = document.getElementById('modalFilter');
const openModalAction = document.getElementsByClassName('openModalAction');

for (let i = 0; i < openModalAction.length; i++) {
  const element = openModalAction[i];

  element.addEventListener('click', (e) => {
    const modalId = e.target.dataset.dowhat;
    const modal = document.getElementById(modalId);
    const actionBtnId = modal.dataset.actionbtn;
    const actionBtn = document.getElementById(actionBtnId);

    modal.style.display = 'flex';
    modalFilter.style.display = 'flex';

    actionBtn.addEventListener('click', (e) => {
      e.preventDefault();
      const who = e.target.dataset.who;
      const selectId = e.target.dataset.select;
      const select = document.getElementById(selectId);
      const optionSelected = select.options[select.selectedIndex];
      const selectedElementId = optionSelected.dataset.id;

      let url = null;

      switch (who) {
        case "updateEquipment":
        case "updateSoftware":
        case "updateProvider":
        case "updateMalfunction":
        case "updateAssistance":
          url = "../pages/" + who + ".php?id=" + selectedElementId
          window.location.href = url;

          break;

        case "lendEquipment":
          const formId = e.target.attributes.form.nodeValue;
          document.getElementById('selectedEquipmentId').value = selectedElementId;

          const form = document.getElementById(formId);
          form.submit();

          break;
        case "retireEquipment":
        case "returnEquipment":
          url = "../actions/" + who + ".php?id=" + selectedElementId
          window.location.href = url;

          break;
        case "deleteEquipment":
        case "deleteSoftware":
        case "deleteProvider":
        case "deleteMalfunction":
        case "deleteAssistance":
          if (confirm('Tem a certeza que deseja apagar este dado?')) {
            url = "../actions/" + who + ".php?id=" + selectedElementId
            window.location.href = url;
          } else {
            closeAllModals();
          }
          break;

        default:
          console.log(who);
          break;
      }
    })
  })


}
//Close all modals
window.addEventListener("click", (e) => {
  if (e.target == modalFilter) {
    closeAllModals(e)
  }
});

function closeAllModals(e) {
  const modalContent = document.getElementsByClassName('modalContent');
  for (let k = 0; k < modalContent.length; k++) {
    const element = modalContent[k];
    element.style.display = 'none';
    modalFilter.style.display = 'none';
  }
}