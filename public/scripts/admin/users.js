import { hasCookieSet } from "../utils/cookie.js";
import { fetchAPI } from "../utils/api.js";

// Verifica o cookie de autenticação
if (!hasCookieSet("token")) {
    window.location.replace("http://localhost:8000/pages/login.html");
}

async function renderUsersPage() {
    const res = await fetchAPI("/content/users/all.php");

    if (res.status === 401)
        window.location.replace("http://localhost:8000/pages/login.html");

    const users = res.data;

    console.log(users);

    const cardsItems = users.map(user => {
        return `
            <article class="card" id="${user.id}">
                <div class="card__image" style="background-image: url('${user.pfp_url}');">
                </div>
                <div class="card__text">
                    <h3 class="card__title">@${user.username}</h3>
                    <span class="card__span">Nome Completo: ${user.name}</span>
                    <span class="card__span">Entrou em: ${new Date(user.datetime_register).toLocaleString()}</span>
                    <span class="card__span">Email: ${user.contact_email}</span>
                    <span class="card__span">Telefone: ${user.contact_phone}</span>
                    <div class="card__buttons">
                        <button class="card__button card__button--ban" value="${user.id}">${user.status == 'active' ? 'BANIR' : 'DESBANIR'}</button>
                        <a class="card__button card__button--chat" href="./chats.html">VER NO CHAT</a>
                    </div>
                </div>
            </article>
        `;
    }).join("");

    document.querySelector(".cards").innerHTML = cardsItems;

    const allButtons = document.querySelectorAll(".card__button--ban");

    allButtons.forEach(button => {
        button.addEventListener("click", async (e) => {
            e.preventDefault();

            const body = {
                user_id: e.currentTarget.value
            };

            if (e.currentTarget.textContent === "BANIR") {
                const res = await fetchAPI("/content/users/ban.php", "POST", body);

                if (res.status === 200)
                    e.target.textContent = "DESBANIR";
                else
                    alert("Ocorreu um erro ao tentar banir o usuário: " + res.data.detail);
            }
            else if (e.currentTarget.textContent === "DESBANIR") {
                const res = await fetchAPI("/content/users/unban.php", "POST", body);

                if (res.status === 200)
                    e.target.textContent = "BANIR";
                else
                    alert("Ocorreu um erro ao tentar desbanir o usuário: " + res.data.detail);
            }
        });
    });
}

renderUsersPage();