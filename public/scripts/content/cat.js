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

    cat.personalities.forEach(personality => {
        document.querySelector(".heading__personalities").innerHTML += `<div class="heading__personality">${personality.name}</div>`
        document.querySelector(".about__personalities").innerHTML +=
            `
            <li class="about__personality">
                <p><strong><span class="item__icon material-symbols-outlined fill">favorite</span> ${personality.name}:
                    </strong>${personality.description}</p>
            </li>
        `;
    });

    cat.vaccines.forEach(vaccine => {
        document.querySelector(".about__vaccines").innerHTML +=
            `
            <li class="about__vaccine">
                <p><strong><span class="item__icon material-symbols-outlined fill">syringe</span> Vacinado contra ${vaccine.name}:
                    </strong>${vaccine.description}</p>
            </li>
        `;
    });

    cat.diseases.forEach(disease => {
        document.querySelector(".about__diseases").innerHTML +=
            `
            <li class="about__vaccine">
                <p><strong><span class="item__icon material-symbols-outlined fill">syringe</span> Portador de ${disease.name}:
                    </strong>${disease.description}</p>
            </li>
        `;
    });

    document.querySelector(".physical_description").textContent = cat.physical_description;
    document.querySelector(".about__title").textContent = `Conheça ${cat.sex == "M" ? "o" : "a"} ${cat.name}`;
}

renderCatPage();