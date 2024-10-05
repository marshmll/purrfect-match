import { deleteCookie } from "../utils/cookie.js";

const logoffBtn = document.getElementById("logoff");

logoffBtn.addEventListener("click", (e) => {
    e.preventDefault();

    deleteCookie("token");

    document.location.replace("http://localhost/purrfect-match/pages/login.html")
})