const filterInputs = document.getElementsByName("filter");

for (let i = 0; i < filterInputs.length; i++) {
    filterInputs[i].addEventListener('keyup', (e) => {
        let inputValue = e.target.value.toUpperCase();

        const targetName = e.target.dataset.filtername;
        const target = document.getElementById(targetName);

        if (target.nodeName == "SELECT") {
            const options = target.options;
            
            if(inputValue !== "") {
                for (let k = 0; k < options.length; k++) {
                    let e = options[k];
                    let eValue = e.text.toUpperCase();
    
                    if (eValue.match(inputValue)) {
                        e.style.display = "";
                        e.selected = "selected"
                    } else {
                        e.style.display = "none";
                    }
                }
            } else {
                target.selectedIndex = target.querySelector('[disabled]').index //Retorna o placeholder como selected

                for (let k = 0; k < options.length; k++) {
                    options[k].style.display = ""
                }
            }
        }
    })
}
