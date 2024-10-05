import { fetchAPI } from "../utils/api.js";
import { setCookie } from "../utils/cookie.js";
import { toMySQLDatetime } from "../utils/mySQLDatetime.js";

const registerForm = document.getElementById("form");
const feedbackSpan = document.querySelector(".register__invalid");

registerForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(registerForm);
    formData.append(
        "datetime_register",
        toMySQLDatetime(new Date())
    );

    const formURLEncoded = new URLSearchParams(formData).toString();

    const headers = new Headers({
        accept: "application/json",
        "Content-Type": "application/x-www-form-urlencoded",
    });

    const res = await fetchAPI("register.php", "POST", formURLEncoded, headers);

    if (res.status != 201) {
        feedbackSpan.textContent = res.data.detail;
        return false;
    }

    setCookie("token", res.data.access_token, 7);
    document.location.replace("http://localhost:8000/pages/fyp.html");
});
