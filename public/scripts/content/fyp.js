import { fetchAPI } from "../utils/api.js";
import { hasCookieSet, deleteCookie } from "../utils/cookie.js";
import { toMySQLDatetime } from "../utils/mySQLDatetime.js";

if (!hasCookieSet("token"))
    window.location.replace("http://localhost:8000/pages/login.html");

async function renderForYouPage() {
    const container = document.querySelector(".cards");

    let response = await fetchAPI("content/cats/all.php");

    if (response.status != 200) {
        deleteCookie("token");
        window.location.replace("http://localhost:8000/pages/login.html");
        return;
    }

    const cats = response.data;
    container.innerHTML = "";

    cats.forEach((cat) => {
        let personalities = "";

        cat.personalities.forEach((personality) => {
            personalities += `<div class="card__personality">${personality}</div>\n`;
        });

        container.innerHTML += `
        <article class="card">
            <a class="card__image" href="/pages/cat.html?id=${cat.id}" style="background-image: url('${cat.picture_url}');"></a>
            <button class="card__fav" title="Adicionar aos favoritos">
                <span class="item__icon material-symbols-outlined ${cat.favorite ? " marked fill" : "" }"
                    id="${cat.id}">favorite</span>
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
            "content/users/favorites/add.php",
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
            "content/users/favorites/remove.php",
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

renderForYouPage();
