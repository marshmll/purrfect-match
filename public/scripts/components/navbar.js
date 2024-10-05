import { deleteCookie } from "../utils/cookie.js";

const logoffBtn = document.getElementById("logout");

logoffBtn.addEventListener("click", (e) => {
    e.preventDefault();

    deleteCookie("token");

    document.location.replace("http://localhost:8000/pages/login.html")
})