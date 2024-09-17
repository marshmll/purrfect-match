import { deleteCookie, hasCookieSet, setCookie } from "./cookie.js";

if (hasCookieSet("token")) {
    document.location.replace("http://localhost/purrfect-match/pages/fyp.html");
    // deleteCookie("token");
}

const loginForm = document.getElementById("form");
const feedbackSpan = document.querySelector(".login__invalid");

loginForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    if (hasCookieSet("token")) {
        document.location.replace("http://localhost/purrfect-match/pages/fyp.html");
    }

    const formData = Object.fromEntries(new FormData(e.target));
    const formURLEncoded = new URLSearchParams(formData).toString();

    const headers = new Headers({
        accept: "application/json",
        "Content-Type": "application/x-www-form-urlencoded",
    });

    let data = await fetch("http://localhost/purrfect-match/php/auth.php", {
        method: "POST",
        headers: headers,
        body: formURLEncoded,
    }).then((res) => {
        if (res.status == 401) {
            feedbackSpan.textContent = "Usuário ou senha incorretos";
            return false;
        }

        feedbackSpan.textContent = "";
        return res.json();
    });

    if (data) {
        setCookie("token", data.access_token, 7);
        document.location.replace("http://localhost/purrfect-match/pages/fyp.html");
    }
});
