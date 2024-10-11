import { hasCookieSet } from "../utils/cookie.js";
import { fetchAPI } from "../utils/api.js";

if (!hasCookieSet("token"))
    window.location.replace("http://localhost:8000/pages/login.html");

async function renderCatPage() {
    const urlParams = Object.fromEntries(new URLSearchParams(window.location.search).entries());

    const res = await fetchAPI("content/cats/cat.php", "POST", urlParams);

    if (res.status != 200) {
        window.location.replace("http://localhost:8000/pages/fyp.html")
    }

    console.log(res.data[0])

    setCatData(res.data[0]);
}

function setCatData(cat) {
    document.querySelector(".heading__picture").style.backgroundImage = `url('${cat.picture_url}')`
    document.querySelector(".heading__name").textContent = cat.name;
    document.querySelector(".heading__age").textContent = `${cat.age} ano(s)`
    document.querySelector(".heading__sex").textContent = cat.sex == "M" ? "Macho" : "Fêmea";
    document.querySelector(".heading__personalities").innerHTML = "";
    cat.personalities.forEach(personality => {
        document.querySelector(".heading__personalities").innerHTML += `<div class="heading__personality">${personality}</div>`
    });

    document.querySelector(".physical_description").innerHTML = cat.physical_description;
}

renderCatPage();