import { hasCookieSet } from "../utils/cookie.js";
import { fetchAPI } from "../utils/api.js";

let rescues = [];

// Verifica o cookie de autenticação
if (!hasCookieSet("token")) {
    window.location.replace("http://localhost:8000/pages/login.html");
}

async function renderRescuesPage() {
    const res = await fetchAPI("/content/rescues/all.php");

    if (res.status === 401)
        window.location.replace("http://localhost:8000/pages/login.html");

    rescues = res.data;

    console.log(rescues);

    const rescuesItems = rescues.map((rescue) => {
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
            <article class="card" id="${rescue.user_id + "|" + rescue.request_datetime}">
                <div class="card__image"
                    style="background-image: url('${rescue.requester_pfp_url}');">
                </div>
                <div class="card__text">
                    <h3 class="card__title">Solicitação de @${rescue.requester_username}</h3>
                    <span class="card__span">Solicitante: ${rescue.requester_name}</span>
                    <span class="card__span card__status">Status do resgate: <strong
                            class="${rescue.status}">${status}</strong></span>
                    <span class="card__span card__date">Data de solicitação: ${new Date(rescue.request_datetime).toLocaleString()}</span>
                    <span class="card__span card__date">Data de conclusão: ${rescue.closure_datetime ? new Date(rescue.closure_datetime).toLocaleString() : "--/--/----"}</span>
                    ${rescue.status === 'pending' ?
                `<div class="card__buttons">
                        <button class="card__button card__button--reject" value="${rescue.user_id + "|" + rescue.request_datetime}">RECUSAR</button>
                        <button class="card__button card__button--approve" value="${rescue.user_id + "|" + rescue.request_datetime}">APROVAR</button>
                        <button class="card__button card__button--details" value="${rescue.user_id + "|" + rescue.request_datetime}">DETALHES</button>
                    </div>` : (rescue.status === 'approved' ? `<div class="card__buttons">
                        <button class="card__button card__button--conclude" value="${rescue.user_id + "|" + rescue.request_datetime}">CONCLUIR</button>
                    </div>` : `
                    <div class="card__buttons">
                        <button class="card__button card__button--details" value="${rescue.user_id + "|" + rescue.request_datetime}">DETALHES</button>
                    </div>
                    `)}
                </div>
            </article>
        `;
    }).join("");

    document.querySelector(".cards").innerHTML = rescuesItems;

    const allDetailButtons = document.querySelectorAll(".card__button--details");
    const allAproveButtons = document.querySelectorAll(".card__button--approve");
    const allRejectButtons = document.querySelectorAll(".card__button--reject");
    const allConcludeButtons = document.querySelectorAll(".card__button--conclude");

    allDetailButtons.forEach(button => {
        button.addEventListener("click", async (e) => {
            e.preventDefault();

            const modal = document.querySelector(".modal");
            const container = document.querySelector(".container");
            const [user_id, request_datetime] = e.target.value.split("|");

            const rescue = rescues.filter(rescue => rescue.user_id === user_id && rescue.request_datetime == request_datetime)[0];

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

            container.innerHTML = `
            <button class="container__close">
                <span class="item__icon material-symbols-outlined">close</span>
            </button>
            <ul class="rescue">
                <li class="rescue__item">
                    <strong>Solicitante:</strong> ${rescue.requester_name} (@${rescue.requester_username})
                </li>
                <li class="rescue__item">
                    <strong>Status:</strong> ${status}
                </li>
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

            modal.classList.remove("hidden");

            document.querySelector(".container__close").addEventListener("click", () => {
                modal.classList.add("hidden");
            });
        });
    });

    allAproveButtons.forEach(button => {
        button.addEventListener("click", async (e) => {
            e.preventDefault();

            const [user_id, request_datetime] = e.target.value.split("|");

            const body = {
                user_id,
                request_datetime
            };

            const res = await fetchAPI("/content/rescues/approve.php", "POST", body);

            if (res.status === 200)
                renderRescuesPage();
        });
    });

    allRejectButtons.forEach(button => {
        button.addEventListener("click", async (e) => {
            e.preventDefault();

            const [user_id, request_datetime] = e.target.value.split("|");

            const body = {
                user_id,
                request_datetime
            };

            const res = await fetchAPI("/content/rescues/reject.php", "POST", body);

            if (res.status === 200)
                renderRescuesPage();
        });
    });

    allConcludeButtons.forEach(button => {
        button.addEventListener("click", async (e) => {
            e.preventDefault();

            const [user_id, request_datetime] = e.target.value.split("|");

            const body = {
                user_id,
                request_datetime
            };

            const res = await fetchAPI("/content/rescues/conclude.php", "POST", body);

            if (res.status === 200)
                renderRescuesPage();
        });
    });
}

renderRescuesPage();