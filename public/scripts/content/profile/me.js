import { fetchAPI } from "../../utils/api.js";
import { deleteCookie, getCookie, hasCookieSet } from "../../utils/cookie.js";

if (!hasCookieSet("token"))
    window.location.replace("http://localhost/purrfect-match/pages/login.html");

async function renderContent() {
    const title = document.querySelector(".head__username");

    const res = await fetchAPI("content/profile/me.php");

    if (res.status == 401) {
        deleteCookie('token');
        window.location.replace("http://localhost/purrfect-match/pages/login.html");
    }

    const user = res.data;

    title.textContent = `${user.name} (@${user.username})`;

    document.getElementById("name").value = user.name;
    document.getElementById("username").value = user.username;
    document.getElementById("date_birth").value = user.date_birth;
    document.getElementById("contact_email").value = user.contact_email;
    document.getElementById("contact_phone").value = user.contact_phone;
}

renderContent();
