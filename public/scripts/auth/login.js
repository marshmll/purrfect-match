import { fetchAPI } from "../utils/api.js";
import { hasCookieSet, setCookie } from "../utils/cookie.js";

if (hasCookieSet("token")) {
    document.location.replace("http://localhost:8000/pages/fyp.html");
}

const loginForm = document.getElementById("form");
const feedbackSpan = document.querySelector(".login__invalid");

loginForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    if (hasCookieSet("token")) {
        document.location.replace("http://localhost:8000/pages/fyp.html");
    }

    const formData = Object.fromEntries(new FormData(e.target));
    const formURLEncoded = new URLSearchParams(formData).toString();

    const headers = new Headers({
        accept: "application/json",
        "Content-Type": "application/x-www-form-urlencoded",
    });

    const res = await fetchAPI("auth.php", "POST", formURLEncoded, headers);

    if (res.status == 401) {
        feedbackSpan.textContent = `Usuário ou senha incorretos.`;
    }
    else if (res.status != 200) {
        feedbackSpan.textContent = `Ocorreu um erro no processamento da requisição. Erro: ${res.status}`;
    }
    else {
        setCookie("token", res.data.access_token, 7);
        document.location.replace(`http://localhost:8000${res.data.redirect || "/pages/fyp.html"}`);
    }
});
