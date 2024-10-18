import { hasCookieSet } from "../utils/cookie.js";
import { fetchAPI } from "../utils/api.js";

// Verifica o cookie de autenticação
if (!hasCookieSet("token")) {
    window.location.replace("http://localhost:8000/pages/login.html");
}

// Função principal para renderizar a página do gato
async function renderCatPage() {
    const urlParams = new URLSearchParams(window.location.search);
    const res = await fetchAPI("content/cats/cat.php", "POST", Object.fromEntries(urlParams.entries()));

    if (res.status !== 200) {
        window.location.replace("http://localhost:8000/pages/fyp.html");
    } else {
        const cat = res.data[0];
        console.log(cat);
        setCatData(cat);
    }
}

// Atualiza os dados visuais do gato na página
function setCatData(cat) {
    setCatHeader(cat);
    setCatPersonalities(cat.personalities);
    setCatVaccines(cat.vaccines);
    setCatDiseases(cat.diseases);
    document.querySelector(".physical_description").textContent = cat.physical_description;
    document.querySelector(".about__title").textContent = `Conheça ${cat.sex === "M" ? "o" : "a"} ${cat.name}`;
}

// Define o cabeçalho do gato
function setCatHeader(cat) {
    document.querySelector(".heading__picture").style.backgroundImage = `url('${cat.picture_url}')`;
    document.querySelector(".heading__name").textContent = cat.name;
    document.querySelector(".heading__age").textContent = `${cat.age} ano(s)`;
    document.querySelector(".heading__sex").textContent = cat.sex === "M" ? "Macho" : "Fêmea";
}

// Adiciona as personalidades do gato
function setCatPersonalities(personalities) {
    const headingPersonalities = document.querySelector(".heading__personalities");
    const aboutPersonalities = document.querySelector(".about__personalities");

    aboutPersonalities.innerHTML = "";

    personalities.forEach(({ name, description }) => {
        headingPersonalities.innerHTML += `<div class="heading__personality">${name}</div>`;
        aboutPersonalities.innerHTML += `
            <li class="about__personality">
                <p><strong><span class="item__icon material-symbols-outlined fill">favorite</span> ${name}:
                </strong>${description}</p>
            </li>
        `;
    });
}

// Adiciona as vacinas do gato
function setCatVaccines(vaccines) {
    const aboutVaccines = document.querySelector(".about__vaccines");

    aboutVaccines.innerHTML = "";

    vaccines.forEach(({ name, description }) => {
        aboutVaccines.innerHTML += `
            <li class="about__vaccine">
                <p><strong><span class="item__icon material-symbols-outlined fill">syringe</span> ${name}:
                </strong>${description}</p>
            </li>
        `;
    });
}

// Adiciona as doenças do gato
function setCatDiseases(diseases) {
    const aboutDiseases = document.querySelector(".about__diseases");

    diseases.forEach(({ name, description }) => {
        aboutDiseases.innerHTML += `
            <li class="about__vaccine">
                <p><strong><span class="item__icon material-symbols-outlined fill">syringe</span> Portador de ${name}:
                </strong>${description}</p>
            </li>
        `;
    });
}

// Inicializa a página
renderCatPage();
