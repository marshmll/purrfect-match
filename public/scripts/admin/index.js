import { hasCookieSet } from "../utils/cookie.js";
import { fetchAPI } from "../utils/api.js";

// Verifica o cookie de autenticação
if (!hasCookieSet("token")) {
    window.location.replace("http://localhost:8000/pages/login.html");
}

async function renderIndexPage() {
    const res = await fetchAPI("/content/cats/all.php");

    if (res === 401)
        window.location.replace("http://localhost:8000/pages/login.html");

    const cats = res.data;

    const catCards = cats.map(cat => {
        return `
            <article class="card" id="${cat.id}">
                <div class="card__image" style="background-image: url('${cat.picture_url}');"></div>
                <button class="card__button card__edit" title="Editar" value="${cat.id}">
                    <span class="item__icon material-symbols-outlined">edit</span>
                </button>
                <button class="card__button card__delete" title="Deletar" value="${cat.id}">
                    <span class="item__icon material-symbols-outlined">delete</span>
                </button>
                <div class="card__text">
                    <h3 class="card__name">${cat.name}</h3>
                    <span class="card__age">${cat.age} ano(s)</span>
                </div>
            </article>
        `;
    }).join("");

    document.querySelector(".cards").innerHTML = catCards;

    const allDeleteButtons = document.querySelectorAll(".card__delete");

    allDeleteButtons.forEach(button => {
        button.addEventListener("click", async (e) => {
            e.preventDefault();
            if (!confirm("Deseja excluir o registro?\nATENÇÃO: Esta ação não poderá ser revertida."))
                return;

            const cat_id = e.currentTarget.value;

            const body = {
                cat_id
            };

            const res = await fetchAPI("/content/cats/delete.php", "POST", body);

            if (res.status !== 200)
                alert(`Ocorreu um erro durante a deleção do registro: ${res.data.detail}`);

            document.getElementById(cat_id).remove();
        });
    });
}

renderIndexPage();