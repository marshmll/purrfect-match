import { hasCookieSet } from "../utils/cookie.js";
import { fetchAPI } from "../utils/api.js";
import { toMySQLDatetime } from "../utils/mySQLDatetime.js";

if (!hasCookieSet("token"))
    window.location.replace("http://localhost/purrfect-match/pages/login.html");

async function renderFavoritesPage() {
    const container = document.querySelector(".cards");

    let response = await fetchAPI("content/favorites.php");

    if (response.status != 200) {
        deleteCookie("token");
        window.location.replace("http://localhost/purrfect-match/pages/login.html");
        return;
    }

    const cats = response.data;

    if (cats.length == 0) {
        container.innerHTML = "<p>Adicione gatinhos aos seus favoritos para vê-los aqui.</p>";
        return;
    }

    cats.forEach((cat) => {
        let personalities = "";

        cat.personalities.forEach((personality) => {
            personalities += `<div class="card__personality">${personality}</div>\n`;
        });

        container.innerHTML += `
        <article class="card">
            <a
                class="card__image"
                href="/"
                style="
                    background-image: url('https://images.ctfassets.net/ub3bwfd53mwy/5WFv6lEUb1e6kWeP06CLXr/acd328417f24786af98b1750d90813de/4_Image.jpg?w=750');
                "
            ></a>
            <button class="card__fav" title="Adicionar aos favoritos">
                <span class="item__icon material-symbols-outlined ${cat.favorite ? "marked fill" : ""}" id="${cat.id}"
                    >favorite</span 
                >
            </button>
            <div class="card__text">
                <h3 class="card__name">${cat.name}</h3>
                <span class="card__age">${cat.age} ano(s)</span>
            </div>
            <div class="card__personalities">
                ${personalities}
            </div>
        </article>
        `;
    });

    document.querySelectorAll(".card__fav").forEach((button) => {
        button.addEventListener('click', (e) => favoriteToggle(e.target));
    })

    console.log(cats);
}

async function favoriteToggle(target) {
    const isMarked = target.classList.contains('marked');

    if (!isMarked) {
        let response = await fetchAPI(
            "content/add_favorite.php",
            "POST",
            {
                cat_id: parseInt(target.id),
                choice_datetime: toMySQLDatetime(new Date())
            }
        );

        if (response.status == 201) {
            target.classList.add('marked');
            target.classList.add('fill');
        }
    }
    else {
        let response = await fetchAPI(
            "content/remove_favorite.php",
            "POST",
            {
                cat_id: parseInt(target.id),
            }
        );

        if (response.status == 200) {
            target.classList.remove('marked');
            target.classList.remove('fill');
        }
    }
}

renderFavoritesPage();