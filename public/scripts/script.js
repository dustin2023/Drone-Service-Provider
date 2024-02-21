function scrollToCategories() {
    var categoriesSection = document.querySelector('#categories');
    var offset = categoriesSection.offsetTop - 100; // hier wird 100 als zusätzliche Pixelanzahl verwendet
    window.scrollTo({top: offset, behavior: 'smooth'});
}

function scrollToWohnimmobilien() {
    var categoriesSection = document.querySelector('#wohnimmobilien');
    var offset = categoriesSection.offsetTop - 100;
    window.scrollTo({top: offset, behavior: 'smooth'});
}

function scrollToBauindustrie() {
    var categoriesSection = document.querySelector('#bauindustrie');
    var offset = categoriesSection.offsetTop - 100;
    window.scrollTo({top: offset, behavior: 'smooth'});
}

function scrollToFabrikenMuseen() {
    var categoriesSection = document.querySelector('#fabrikenMuseen');
    var offset = categoriesSection.offsetTop - 100;
    window.scrollTo({top: offset, behavior: 'smooth'});
}

function scrollToHotelsGastronomie() {
    var categoriesSection = document.querySelector('#hotels-gastronomie');
    var offset = categoriesSection.offsetTop - 100;
    window.scrollTo({top: offset, behavior: 'smooth'});
}

function scrollToFirmengelaendeEvents() {
    var categoriesSection = document.querySelector('#firmengelände-firmenevents');
    var offset = categoriesSection.offsetTop - 100;
    window.scrollTo({top: offset, behavior: 'smooth'});
}

function scrollToSportaktivitaetenSportstaetten() {
    var categoriesSection = document.querySelector('#sportaktivitäten-sportstätten');
    var offset = categoriesSection.offsetTop - 100;
    window.scrollTo({top: offset, behavior: 'smooth'});
}

// Domain-Schicht
function showLoginSuccessToast() {
    const toast = document.getElementById("toast");
    const message = document.getElementById("toast-message");
    message.textContent = "Login erfolgreich!";
    toast.classList.add("success");
    showToast(toast);
}

function showLoginFailedToast() {
    const toast = document.getElementById("toast");
    const message = document.getElementById("toast-message");
    message.textContent = "Login fehlgeschlagen! Bitte überprüfen Sie Ihre Eingaben.";
    toast.classList.add("failed");
    showToast(toast);
}

function showLogoutSuccessToast() {
    const toast = document.getElementById("toast");
    const message = document.getElementById("toast-message");
    message.textContent = "Logout erfolgreich!";
    toast.classList.add("success");
    showToast(toast);
}

function showOrderSuccessToast() {
    const toast = document.getElementById("toast");
    const message = document.getElementById("toast-message");
    message.textContent = "Danke für deinen Auftrag! Wir melden uns in der nächsten Zeit bei dir.";
    toast.classList.add("success");
    showToast(toast);
}

function showRegistrationSuccessToast() {
    const toast = document.getElementById("toast");
    const message = document.getElementById("toast-message");
    message.textContent = "Registrierung erfolgreich!";
    toast.classList.add("success");
    showToast(toast);
}

function showRegistrationFailedToast() {
    const toast = document.getElementById("toast");
    const message = document.getElementById("toast-message");
    message.textContent = "Registrierung fehlgeschlagen!";
    toast.classList.add("failed");
    showToast(toast);
}

function showLoginFirstToast() {
    const toast = document.getElementById("toast");
    const message = document.getElementById("toast-message");
    message.textContent = "Melde dich zuerst an oder erstelle ein kostenloses Konto";
    toast.classList.add("failed");
    showToast(toast);
}


function showErrorToast() {
    const toast = document.getElementById("toast");
    const message = document.getElementById("toast-message");
    message.textContent = "Ein Fehler ist aufgetreten - bitte versuche es später erneut.";
    toast.classList.add("failed");
    showToast(toast);
}


// Schnittstellen-Adapter-Schicht
function showToast(toast) {
    toast.classList.remove("hide");
    const toastPopup = new bootstrap.Toast(toast, {
        autohide: true,
        delay: 2000
    });
    setTimeout(() => {
        toastPopup.show();
    }, 1000);
}

// Infrastruktur-Schicht
if (document.cookie.includes("Show-Login-Success-Toast=true")) {
    showLoginSuccessToast();
}
if (document.cookie.includes("Show-Login-Failed-Toast=true")) {
    showLoginFailedToast();
}
if (document.cookie.includes("Show-Logout-Success-Toast=true")) {
    showLogoutSuccessToast();
}
if (document.cookie.includes("Show-Order-Success-Toast=true")) {
    showOrderSuccessToast();
}
if (document.cookie.includes("Show-Registration-Success-Toast=true")) {
    showRegistrationSuccessToast();
}
if (document.cookie.includes("Show-Registration-Failed-Toast=true")) {
    showRegistrationFailedToast();
}
if (document.cookie.includes("Show-Login-First-Toast=true")) {
    showLoginFirstToast();
}
if (document.cookie.includes("Show-Error-Toast=true")) {
    showErrorToast();
}