$(document).ready(function(){
	$(".search-input").keyup(function(){
		filter = new RegExp($(this).val(),'i');
		id = $(this).attr('data-filtername');
		console.log("#" + id + " tr")
		
		$("#" + id + " tr").filter(function(){
			$(this).each(function(){
				found = false;
				$(this).children().each(function(){
					content = $(this).html();
					if(content.match(filter))
					{
						found = true
					}
				});
				if(!found)
				{
					$(this).hide();
				}
				else
				{
					$(this).show();
				}
			});
		});
	});
});

const tr = document.querySelectorAll('tbody tr');

tr.forEach(element => {
    element.addEventListener('click', (e) => {
        const type = e.path[2].id;
        const id = e.path[1].dataset.id;
        let path = null;
        
        switch (type) {
            case 'assistance':
                path = 'assistance'
                break;
                
            case 'malfunctions':
                path = 'malfunction'
                break;
                
            case 'equipments':
                path = 'equipment'
                break;
                
            case 'softwares':
                path = 'software'
                break;
        }
        
        if (path !== null) {
            window.location.href = path + '.php?id=' + id;
        }
    })
})