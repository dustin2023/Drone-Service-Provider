//Not used anymore
function showContent(data) {
    console.log(data); // Prüfen, ob die Daten zurückgegeben werden
    // get the elements we need
    let template = document.getElementsByTagName("template")[0];
    let container = document.getElementById("list-group");
    console.log(template, container); // Prüfen, ob die Elemente gefunden werden

    // fill the template
    //template.content.querySelector("h1").textContent += data.;
    template.content.getElementById("address").textContent += data.address;
    template.content.getElementById("description").textContent += data.description;
    console.log(template.content); // Prüfen, ob die Template-Inhalte aktualisiert wurden

    // append a clone to the container
    let clone = template.content.cloneNode(true);
    container.appendChild(clone);
}

// get the blog posts URL key from the called URL
let urlKey = window.location.pathname;
urlKey = urlKey.split("/").pop();
urlKey = urlKey.split("?").shift();

// use the Fetch API to get our blog post
fetch('/api/v/Auftrag/' + urlKey)
    .then(response => {
        if (!response.ok) {
            // TODO: proper error handling! An alert is the wort idea ever. Maybe a toast and an error text?
            alert('Could not load blog post with URL key "' + urlKey + '"');
        }
        return response.json();
    })
    .then(data => showContent(data));