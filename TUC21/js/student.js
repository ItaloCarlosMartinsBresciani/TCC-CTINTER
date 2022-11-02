let btn = window.document.querySelectorAll(".sidebar-nav li");
let content = window.document.querySelector("#content");

btn.forEach((element, index) => {
    element.addEventListener("click", () => {
        let url;

        btn.forEach((element) => {
            element.classList.remove("active");
        });

        element.classList.add("active");

        switch (index) {
            case 0:
                url = "components/homeStudent.php"; //home
                break;
            case 1:
                url = "components/professorStudent.php";  //professores
                break;
            case 2:
                url = "components/companyStudent.php?"; //empresa
                break;
            case 3:
                url = "components/coordinatorStudent.php"; //coordenador de estágio
                break;
            case 4:
                url = "components/profileStudent.php"; //perfil
                break;
        }

        let request = new XMLHttpRequest();
        request.open("POST", url);
        request.setRequestHeader(
            "Content-Type",
            "application/x-www-form-urlencoded"
        );

        request.send();

        request.onreadystatechange = () => {
            if (request.readyState == 4 && request.status == 200) {
                content.innerHTML = request.responseText;
            }
        };
    });
});

function openFeedbackModal(modalId, modalTitle, modalMessage, button = -1) {
    window.addEventListener("load", () => {
        let myModal = new bootstrap.Modal(window.document.getElementById(modalId));
        let myModalTitle = window.document.querySelector(
            "#" + modalId + " .modal-title"
        );
        let myModalMessage = window.document.querySelector(
            "#" + modalId + " .modal-body"
        );

        myModalTitle.innerHTML = modalTitle;
        myModalMessage.innerHTML = modalMessage;

        if (button != -1) {
            btn[button].dispatchEvent(new Event("click"));
        }

        myModal.show();
    });
}
/*function open_modal( id ){
	var maskHeight = $(document).height();
	var maskWidth = $(window).width();
	
	$('#mask').css({'width':maskWidth,'height':maskHeight});

	$('#mask').fadeIn(1000);	
	$('#mask').fadeTo("slow",0.8);	
	
	//Get the window height and width
	var winH = $(window).height();
	var winW = $(window).width();
              
	$(id).css('top',  winH/2-$(id).height()/2);
	$(id).css('left', winW/2-$(id).width()/2);
	$(id).fadeIn(2000); 
};
$(document).ready(function() {	

	$('a[name=modal]').click(function(e) {
		e.preventDefault();
		open_modal( $(this).attr('href') );	
	});

	$('.window .close').click(function (e) {
		e.preventDefault();
		
		$('#mask').hide();
		$('.window').hide();
	});		
	
	$('#mask').click(function () {
		$(this).hide();
		$('.window').hide();
	});
});

function openModalDialog(modalId, modalTitle, modalMessage, button = 0) {
    window.addEventListener("load", () => {
        let myModal = new bootstrap.Modal(window.document.getElementById(modalId));
        let myModalTitle = window.document.querySelector(
            "#" + modalId + " .modal-title"
        );
        let myModalMessage = window.document.querySelector(
            "#" + modalId + " .modal-body"
        );

        myModalTitle.innerHTML = modalTitle;
        myModalMessage.innerHTML = modalMessage;

        if (button != 0) {
            btn[button].dispatchEvent(new Event("click"));
        }

        myModal.show();
    });
}*/

function getUniversityContent() {
    let universityContent = window.document.querySelector("#university-content");

    // Busca

    let textValueSearch = window.document.querySelector("#search-text-university").value;

    let request = new XMLHttpRequest();
    request.open("POST", "content/universityContent.php");
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    request.send("textSearch=" + textValueSearch);

    request.onreadystatechange = () => {
        if (request.readyState == 4 && request.status == 200) {
            universityContent.innerHTML = request.responseText;
        }
    };
}

function getPersonContent() {
    let personContent = window.document.querySelector("#person-content");

    // Busca

    let textValueSearch = window.document.querySelector("#search-text-person").value;

    let request = new XMLHttpRequest();
    request.open("POST", "content/personContent.php");
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    request.send("textSearch=" + textValueSearch);

    request.onreadystatechange = () => {
        if (request.readyState == 4 && request.status == 200) {
            personContent.innerHTML = request.responseText;
        }
    };
}

function getCompanyContent() {
    let companyContent = window.document.querySelector("#company-content");

    // Busca

    let textValueSearch = window.document.querySelector("#search-text-company").value;

    let request = new XMLHttpRequest();
    request.open("POST", "content/companyContent.php");
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    request.send("textSearch=" + textValueSearch);

    request.onreadystatechange = () => {
        if (request.readyState == 4 && request.status == 200) {
            companyContent.innerHTML = request.responseText;
        }
    };
}

window.addEventListener("load", () => {
    if (btn[0] != null) {
        btn[0].dispatchEvent(new Event("click"));
    }
});

function exclude(type, id) {
    let typeString, textModal;

    switch (type) {
        case 10:
            typeString = "person";
            textModal = "pessoa";
            break;
        case 15:
            typeString = "university";
            textModal = "instituição de ensino";
            break;
    }

    let myModal = new bootstrap.Modal(
        window.document.getElementById("excludeModal")
    );
    let myModalTitle = window.document.querySelector(
        "#excludeModal .modal-title"
    );
    let myModalMessage = window.document.querySelector(
        "#excludeModal .modal-body"
    );

    myModalTitle.innerHTML = "Excluir";
    myModalMessage.innerHTML = "Confirmar exclusão desta " + textModal;

    myModalMessage.innerHTML += `
        <input type="text" class="visually-hidden" value="${typeString}" name="type">
        <input type="text" class="visually-hidden" value="${id}" name="id">
    `;

    // btn[2].dispatchEvent(new Event('click'));

    myModal.show();
}

function recycle(type, id) {
    let typeString, textModal;

    switch (type) {
        case 10:
            typeString = "person";
            textModal = "pessoa";
            break;
        case 15:
            typeString = "university";
            textModal = "instituição de ensino";
            break;
    }

    let myModal = new bootstrap.Modal(
        window.document.getElementById("recycleModal")
    );
    let myModalTitle = window.document.querySelector(
        "#recycleModal .modal-title"
    );
    let myModalMessage = window.document.querySelector(
        "#recycleModal .modal-body"
    );

    myModalTitle.innerHTML = "Incluir";
    myModalMessage.innerHTML = "Reativar esta " + textModal;

    myModalMessage.innerHTML += `
        <input type="text" class="visually-hidden" value="${typeString}" name="type">
        <input type="text" class="visually-hidden" value="${id}" name="id">
    `;

    myModal.show();
}
