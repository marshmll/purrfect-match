import { hasCookieSet } from "../../utils/cookie.js";
import { fetchAPI } from "../../utils/api.js";

// Verifica o cookie de autenticação
if (!hasCookieSet("token")) {
    window.location.replace("http://localhost:8000/pages/login.html");
}

let rescues = [];

// Função principal para renderizar a página do gato
async function renderAdoptionsPage() {
    const res = await fetchAPI("/content/users/rescues/all.php");

    if (res.status === 401)
        window.location.replace("http://localhost:8000/pages/login.html");

    rescues = res.data;

    console.log(rescues)

    if (rescues.length === 0) {
        document.querySelector(".cards").innerHTML = "Solicite resgates para vê-los aqui.";
        return;
    }

    const rescuesCards = rescues.map(rescue => {
        let status;
        switch (rescue.status) {
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
        <article class="card" id="${rescue.request_datetime}">
            <div class="card__text">
                <h3 class="card__title">Solicitação de ${new Date(rescue.request_datetime).toLocaleString()}</h3>
                <span class="card__span card__status">Status da adoção: <strong
                        class="${rescue.status}">${status}</strong></span>
                <span class="card__span card__date">Data de conclusão: ${rescue.closure_datetime ? new Date(rescue.closure_datetime).toLocaleDateString() : "--/--/----"}</span>
                <div class="card__buttons">
                    ${rescue.status === 'pending' ? `<button class="card__button card__button--cancel" value="${rescue.request_datetime}">CANCELAR</button>` : ""}
                    <button class="card__button card__button--detail" value="${rescue.request_datetime}">DETALHES</button>
                </div>
            </div>
        </article>
        `;
    }).join("");

    document.querySelector(".cards").innerHTML = rescuesCards;

    const allDetailButtons = document.querySelectorAll(".card__button--detail");

    allDetailButtons.forEach((button) => {
        button.addEventListener("click", (e) => {
            const request_datetime = e.target.value;
            const rescue = rescues.filter((rescue) => rescue.request_datetime === request_datetime)[0];

            const container = document.querySelector(".container");

            container.innerHTML = `
            <button class="container__close">
                <span class="item__icon material-symbols-outlined">close</span>
            </button>
            <ul class="rescue">
                <li class="rescue__item">
                    <strong>Data da solicitação:</strong> ${new Date(rescue.request_datetime).toLocaleString()}
                </li>
                <li class="rescue__item">
                    <strong>Data de conclusão:</strong> ${rescue.closure_datetime ? new Date(rescue.closure_datetime).toLocaleDateString() : "--/--/----"}
                </li>
                <li class="rescue__item">
                    <strong>Endereço:</strong> ${rescue.addr_street}, ${rescue.addr_number}. ${rescue.addr_city}, ${rescue.addr_state}. ${rescue.addr_zipcode}
                </li>
                <li class="rescue__item">
                    <strong>Características:</strong> ${rescue.characteristics}
                </li>
                <li class="rescue__item">
                    <strong>Descrição:</strong> ${rescue.description}
                </li>
            </ul>
            `;

            const modal = document.querySelector(".modal");

            document.querySelector(".container__close").addEventListener("click", () => {
                modal.classList.add("hidden");
            });

            modal.classList.remove("hidden");
        })
    })

    const allCancelButtons = document.querySelectorAll(".card__button--cancel");

    allCancelButtons.forEach((button) => button.addEventListener("click", async (e) => {
        if (!confirm("Tem certeza que deseja cancelar a solicitação?\nATENÇÃO: A AÇÃO NÃO PODERÁ SER DESFEITA."))
            return;

        const request_datetime = e.target.value;
        const res = await fetchAPI("/content/users/rescues/cancel.php", "POST", { request_datetime });

        if (res.status === 200)
            document.getElementById(request_datetime).remove();
    }))
}

renderAdoptionsPage();