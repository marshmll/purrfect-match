import { hasCookieSet, getCookie, deleteCookie } from "./cookie.js";
import { toMySQLDatetime } from "./utils/MySQLDatetime.js";

if (!hasCookieSet("token"))
    window.location.replace("http://localhost/purrfect-match/pages/login.html");

const token = getCookie("token");

async function fetchContent() {
    const headers = new Headers({
        accept: "application/json",
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
    });

    let data = await fetch("http://localhost/purrfect-match/php/fyp/cats.php", {
        method: "POST",
        headers: headers,
    }).then(async (res) => {
        let json = await res.json();

        if (res.status != 200) {
            // deleteCookie("token");
            // window.location.replace(
            //     "http://localhost/purrfect-match/pages/login.html"
            // );
            return json.detail;
        }

        return json;
    });

    return data;
}

async function renderContent() {
    const container = document.querySelector(".cards");

    let cats = await fetchContent();

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

    const headers = new Headers({
        accept: "application/json",
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
    });

    if (!isMarked) {
        await fetch("http://localhost/purrfect-match/php/fyp/add_favorite.php", {
            method: "POST",
            headers: headers,
            body: JSON.stringify({
                cat_id: parseInt(target.id),
                choice_datetime: toMySQLDatetime(new Date()),
            })
        }).then(async (res) => {
            console.log(await res.json());

            target.classList.add('marked');
            target.classList.add('fill');
        });
    }
    else {
        await fetch("http://localhost/purrfect-match/php/fyp/remove_favorite.php", {
            method: "POST",
            headers: headers,
            body: JSON.stringify({
                cat_id: parseInt(target.id),
            })
        }).then(async (res) => {
            console.log(await res.json());

            target.classList.remove('marked');
            target.classList.remove('fill');
        });
    }
}

renderContent();
