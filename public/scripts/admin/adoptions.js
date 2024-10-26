import { hasCookieSet } from "../utils/cookie.js";
import { fetchAPI } from "../utils/api.js";

let adoptions = [];

// Verifica o cookie de autenticação
if (!hasCookieSet("token")) {
    window.location.replace("http://localhost:8000/pages/login.html");
}

async function renderAdoptionsPage() {
    const res = await fetchAPI("/content/adoptions/all.php");

    if (res.status === 401)
        window.location.replace("http://localhost:8000/pages/login.html");

    adoptions = res.data;

    console.log(adoptions);

    const adoptionsItems = adoptions.map((adoption) => {
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
            <article class="card" id="${adoption.user_id + "-" + adoption.cat_id}">
                <div class="card__image"
                    style="background-image: url('${adoption.cat_picture_url}');">
                </div>
                <div class="card__text">
                    <h3 class="card__title">Adoção de ${adoption.cat_name}</h3>
                    <span class="card__span">Solicitante: ${adoption.requester_name}</span>
                    <span class="card__span card__status">Status da adoção: <strong
                            class="${adoption.status}">${status}</strong></span>
                    <span class="card__span card__date">Data de solicitação: ${new Date(adoption.request_datetime).toLocaleString()}</span>
                    <span class="card__span card__date">Data de conclusão: ${adoption.hand_over_datetime ? new Date(adoption.hand_over_datetime).toLocaleString() : "--/--/----"}</span>
                    ${adoption.status === 'pending' ?
                `<div class="card__buttons">
                        <button class="card__button card__button--reject" value="${adoption.user_id + "-" + adoption.cat_id}">RECUSAR</button>
                        <button class="card__button card__button--approve" value="${adoption.user_id + "-" + adoption.cat_id}">APROVAR</button>
                    </div>` : (adoption.status === 'approved' ? `<div class="card__buttons">
                        <button class="card__button card__button--conclude" value="${adoption.user_id + "-" + adoption.cat_id}">CONCLUIR</button>
                    </div>` : "")}
                </div>
            </article>
        `;
    }).join("");

    document.querySelector(".cards").innerHTML = adoptionsItems;

    const allAproveButtons = document.querySelectorAll(".card__button--approve");
    const allRejectButtons = document.querySelectorAll(".card__button--reject");
    const allRejectConclude = document.querySelectorAll(".card__button--conclude");

    allAproveButtons.forEach(button => {
        button.addEventListener("click", async (e) => {
            e.preventDefault();

            const [user_id, cat_id] = e.target.value.split("-");

            const body = {
                user_id,
                cat_id
            };

            const res = await fetchAPI("/content/adoptions/approve.php", "POST", body);

            if (res.status === 200)
                renderAdoptionsPage();
        })
    })

    allRejectButtons.forEach(button => {
        button.addEventListener("click", async (e) => {
            e.preventDefault();

            const [user_id, cat_id] = e.target.value.split("-");

            const body = {
                user_id,
                cat_id
            };

            const res = await fetchAPI("/content/adoptions/reject.php", "POST", body);

            if (res.status === 200)
                renderAdoptionsPage();
        })
    })

    allRejectConclude.forEach(button => {
        button.addEventListener("click", async (e) => {
            e.preventDefault();

            const [user_id, cat_id] = e.target.value.split("-");

            const body = {
                user_id,
                cat_id
            };

            const res = await fetchAPI("/content/adoptions/conclude.php", "POST", body);

            if (res.status === 200)
                renderAdoptionsPage();
        })
    })
}

renderAdoptionsPage();