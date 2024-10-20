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
    document.querySelector('.container__title').textContent = `Deseja solicitar a adoção de ${cat.name}?`;
    document.querySelector(".physical_description").textContent = cat.physical_description;
    document.querySelector(".about__title").textContent = `Conheça ${cat.sex === "M" ? "o" : "a"} ${cat.name}`;

    if (cat.favorite === true) {
        document.querySelector(".heading__button--favorite").textContent = "Favorito";
        document.querySelector(".heading__button--favorite").classList.add("favorite");
    }
    else {
        document.querySelector(".heading__button--favorite").textContent = "Favoritar";
        document.querySelector(".heading__button--favorite").classList.remove("favorited");
    }
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

    if (personalities.length !== 0) aboutPersonalities.innerHTML = "";

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

    if (vaccines.length !== 0) aboutVaccines.innerHTML = "";

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

    if (diseases.length !== 0) aboutDiseases.innerHTML = "";

    diseases.forEach(({ name, description }) => {
        aboutDiseases.innerHTML += `
            <li class="about__vaccine">
                <p><strong><span class="item__icon material-symbols-outlined fill">syringe</span> Portador de ${name}:
                </strong>${description}</p>
            </li>
        `;
    });
}

function showAdoptionModal() {
    document.querySelector(".modal").classList.remove("modal--hidden");
}

function hideAdoptionModal() {
    document.querySelector(".modal").classList.add("modal--hidden");
}

document.querySelector(".heading__button--adopt").addEventListener("click", (e) => {
    e.preventDefault();

    showAdoptionModal();
});

document.querySelector(".container__button--request").addEventListener("click", async (e) => {
    e.preventDefault();

    const urlParams = Object.fromEntries(new URLSearchParams(window.location.search).entries());

    const body = {
        cat_id: urlParams.id
    };

    const res = await fetchAPI('/content/adoptions/request.php', 'POST', body);

    if (res.status !== 201) {
        alert(`Erro: ${res.data.detail}`);
        return;
    }

    window.location.replace(`http://localhost:8000/pages/adoptions/`);
});

document.querySelector(".container__button--cancel").addEventListener("click", (e) => {
    e.preventDefault();

    hideAdoptionModal();
});

document.querySelector(".heading__button--favorite").addEventListener("click", async (e) => {
    e.preventDefault();

    const urlParams = Object.fromEntries(new URLSearchParams(window.location.search).entries());

    const body = {
        cat_id: parseInt(urlParams.id)
    };

    if (e.currentTarget.classList.contains("favorited")) {
        const res = await fetchAPI("/content/users/favorites/remove.php", "POST", body);

        if (res.status === 200) {
            document.querySelector(".heading__button--favorite").textContent = "Favoritar";
            document.querySelector(".heading__button--favorite").classList.remove("favorite");
        }

    } else {
        const res = await fetchAPI("/content/users/favorites/add.php", "POST", body);

        if (res.status === 201) {
            document.querySelector(".heading__button--favorite").textContent = "Favorito";
            document.querySelector(".heading__button--favorite").classList.add("favorite");
        }
    }
})

// Inicializa a página
renderCatPage();
