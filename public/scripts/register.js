import { setCookie } from "./cookie.js";

const registerForm = document.getElementById("form");
const feedbackSpan = document.querySelector(".register__invalid");

registerForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(registerForm);
    formData.append(
        "datetime_register",
        new Date().toISOString().slice(0, 19).replace("T", " ")
    );

    const formURLEncoded = new URLSearchParams(formData).toString();

    const headers = new Headers({
        accept: "application/json",
        "Content-Type": "application/x-www-form-urlencoded",
    });

    let data = await fetch("http://localhost/purrfect-match/php/register.php", {
        method: "POST",
        headers: headers,
        body: formURLEncoded,
    }).then(async (res) => {
        let json = await res.json();
        
        if (res.status != 201) {
            feedbackSpan.textContent = json.detail;
            return false;
        }

        feedbackSpan.textContent = "";
        return json;
    });

    if (data) {
        setCookie("token", data.access_token, 7);
        document.location.replace("http://localhost/purrfect-match/");
    }
});
