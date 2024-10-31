import { hasCookieSet } from "../../utils/cookie.js";
import { fetchAPI } from "../../utils/api.js";

// Verifica o cookie de autenticação
if (!hasCookieSet("token")) {
    window.location.replace("http://localhost:8000/pages/login.html");
}

const form = document.getElementById("rescue-form");


form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(e.currentTarget);
    const body = Object.fromEntries(formData.entries());

    const res = await fetchAPI('/content/rescues/request.php', 'POST', body);

    console.log(res);

    if (res.status === 200)
        window.location.replace("http://localhost:8000/pages/rescues/");
    else
        alert(`Ocorreu um erro durante o processamento da requisição: ${res.data.detail}`);
});
