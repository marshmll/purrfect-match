import { hasCookieSet } from "../../utils/cookie.js";
import { fetchAPI } from "../../utils/api.js";

// Verifica o cookie de autenticação
if (!hasCookieSet("token")) {
    window.location.replace("http://localhost:8000/pages/login.html");
}

// Função principal para renderizar a página do gato
async function renderAdoptionsPage() {
    const res = await fetchAPI("/content/users/adoptions/all.php");

    if (res.status === 401)
        window.location.replace("http://localhost:8000/pages/login.html");

    const adoptions = res.data;

    if (adoptions.length === 0) {
        document.querySelector(".cards").innerHTML = "Solicite adoções para vê-las aqui.";
        return;
    }

    const adoptionsCards = adoptions.map(adoption => {
        let status;
        switch (adoption.status) {
            case "pending":
                status = "Pendente";
                break;
            case "rejected":
                status = "Recusado";
                break;
            case "concluded":
                status = "Concluído";
                break;
            case "approved":
                status = "Aprovado";
                break;
        }

        return `
        <article class="card" id="${adoption.cat_id}">
            <div class="card__image"
                style="background-image: url('${adoption.cat_picture_url}');">
            </div>
            <div class="card__text">
                <h3 class="card__title">${adoption.cat_name}</h3>
                <span class="card__span card__status">Status da adoção: <strong
                        class="${adoption.status}">${status}</strong></span>
                <span class="card__span card__date">Data de solicitação: ${new Date(adoption.request_datetime).toLocaleString()}</span>
                <span class="card__span card__date">Data de conclusão: ${adoption.hand_over_datetime ? new Date(adoption.hand_over_datetime).toLocaleDateString() : "--/--/----"}</span>
                <div class="card__buttons">
                    ${adoption.status === 'pending' ? `<button class="card__button card__button--cancel" value="${adoption.cat_id}">CANCELAR</button>` : ""}
                    <button class="card__button card__button--chat">VER NO CHAT</button>
                </div>
            </div>
        </article>
        `;
    }).join("");

    document.querySelector(".cards").innerHTML = adoptionsCards;

    const allCancelButtons = document.querySelectorAll(".card__button--cancel");

    allCancelButtons.forEach((button) => {
        button.addEventListener("click", async (e) => {
            if (confirm("Deseja cancelar o processo de adoção?\nATENÇÃO: Isto não poderá ser desfeito.")) {
                const body = {
                    cat_id: e.target.value
                };

                const res = await fetchAPI("/content/adoptions/cancel.php", 'POST', body);
                if (res.status === 200)
                    document.getElementById(e.target.value).remove();
            }
        })
    })
}

renderAdoptionsPage();