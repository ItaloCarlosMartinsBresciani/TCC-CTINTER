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
                url = "components/homeCoordinator.php"; //home
                break;
            case 1:
                url = "components/advisorCoordinator.php?"; //funcionários
                break;
            case 2:
                url = "components/studentsCoordinator.php"; //alunos
                break;
            case 3:
                url = "components/internCoordinator.php"; //alunos
                break;
            case 4:
                url = "components/finishedInternshipsCoordinator.php"; //estágios concluídos
            break;
            case 5:
                url = "components/reportsCoordinator.php"; //instituição de ensino
                break;                
            case 6:
                url = "components/internshipPlanCoordinator.php"; //instituição de ensino
                break;
            case 7:
                url = "components/universityCoordinator.php"; //relatórios 
                break;
            case 8:
                url = "components/companyCoordinator.php"; //instituição de ensino
                break;
            case 9:
                url = "components/profileCoordinator.php"; //perfil
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

function openFeedbackModal(modalId, modalTitle, modalMessage, button = 0) {
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
}

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

function getInternContent() {
    let personContent = window.document.querySelector("#person-content");

    // Busca

    let textValueSearch = window.document.querySelector("#search-text-person").value;

    let request = new XMLHttpRequest();
    request.open("POST", "content/internContent.php");
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    request.send("textSearch=" + textValueSearch);

    request.onreadystatechange = () => {
        if (request.readyState == 4 && request.status == 200) {
            personContent.innerHTML = request.responseText;
        }
    };
}

function getFinishedInternshipContent() {
    let personContent = window.document.querySelector("#person-content");

    // Busca

    let textValueSearch = window.document.querySelector("#search-text-person").value;

    let request = new XMLHttpRequest();
    request.open("POST", "content/finishedInternshiContent.php");
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    request.send("textSearch=" + textValueSearch);

    request.onreadystatechange = () => {
        if (request.readyState == 4 && request.status == 200) {
            personContent.innerHTML = request.responseText;
        }
    };
}

function getStudentContent() {
    let personContent = window.document.querySelector("#person-content");

    // Busca

    let textValueSearch = window.document.querySelector("#search-text-person").value;

    let request = new XMLHttpRequest();
    request.open("POST", "content/studentContent.php");
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    request.send("textSearch=" + textValueSearch);

    request.onreadystatechange = () => {
        if (request.readyState == 4 && request.status == 200) {
            personContent.innerHTML = request.responseText;
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
        case 5:
            typeString = "student";
            textModal = "estudante";
            break;
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
        case 5:
            typeString = "student";
            textModal = "estudante";
            break;
        case 10:
            typeString = "person";
            textModal = "pessoa";
            break;
        case 15:
            typeString = "intern";
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

    myModalTitle.innerHTML = "Recuperar";
    myModalMessage.innerHTML = "Recuperar esta " + textModal;

    myModalMessage.innerHTML += `
        <input type="text" class="visually-hidden" value="${typeString}" name="type">
        <input type="text" class="visually-hidden" value="${id}" name="id">
    `;

    myModal.show();
}
