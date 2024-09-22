import { getCookie, hasCookieSet } from "../../utils/cookie.js";

if (!hasCookieSet("token"))
    window.location.replace("http://localhost/purrfect-match/pages/login.html");

const token = getCookie("token");

async function fetchContent() {
    let headers = new Headers({
        accept: "application/json",
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
    });

    let data = await fetch("http://localhost/purrfect-match/php/profile/me.php", {
        method: "POST",
        headers: headers,
    }).then(async (res) => {
        let json = await res.json();

        if (res.status != 200) {
            // deleteCookie("token");
            // window.location.replace(
            //     "http://localhost/purrfect-match/pages/login.html"
            // );
            return json.detail;
        }

        return json;
    });

    return data;
}

async function renderContent() {
    let user = await fetchContent();

    const title = document.querySelector(".head__username");
    title.textContent = `${user.name} (@${user.username})`;
}

renderContent();
