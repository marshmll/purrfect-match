import { fetchAPI } from "../../utils/api.js";
import { deleteCookie, getCookie, hasCookieSet } from "../../utils/cookie.js";

if (!hasCookieSet("token"))
    window.location.replace("http://localhost/purrfect-match/pages/login.html");

const form = document.getElementById("personal_data_form");

form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(e.currentTarget);

    if (formData.get("pfp_url").length == 0)
        formData.delete("pfp_url");

    const formURLEncoded = new URLSearchParams(formData).toString();

    console.log(formURLEncoded);

    const res = await fetchAPI(
        'content/users/profile/update.php',
        'POST',
        formURLEncoded,
        new Headers({
            accept: "application/json",
            "Content-Type": "application/x-www-form-urlencoded",
            Authorization: `Bearer ${getCookie("token")}`,
        })
    );

    if (res.status != 200)
        alert("Ocorreu um erro durante a atualização do usuário: " + res.data.detail);
    else {
        setUserPersonalData(res.data);
        alert("Dados atualizados com sucesso!");
    }
})

async function renderContent() {
    const profile = await fetchAPI("content/users/profile/me.php");

    if (profile.status == 401) {
        deleteCookie('token');
        window.location.replace("http://localhost/purrfect-match/pages/login.html");
    }

    const user = profile.data;

    setUserPersonalData(user);

    const preferences = await fetchAPI("content/users/preferences/all.php");

    if (profile.status == 401) {
        deleteCookie('token');
        window.location.replace("http://localhost/purrfect-match/pages/login.html");
    }

    // console.log(preferences.data);

    const preferencesContainer = document.querySelector(".preferences")
    preferencesContainer.innerHTML = "";

    preferences.data.forEach((preference) => {

        preferencesContainer.innerHTML += `
        <button id="${preference.id}" class="preferences__option ${preference.selected ? 'preferences__option--selected' : ''}" title="${preferences.selected ? 'Remover' : 'Adicionar'}">
            ${preference.selected ? '<span class="item__icon material-symbols-outlined">check_circle</span>' : ""}    
            ${preference.name}
        </button>
        `;
    });

    document.querySelectorAll(".preferences__option")
        .forEach((button) => button.onclick = async () => {
            if (!button.classList.contains("preferences__option--selected")) {

                let res = await fetchAPI(
                    "content/users/preferences/add_personality.php",
                    "POST",
                    {
                        personality_id: button.id
                    }
                );

                if (res.status == 201) {
                    // console.log(res)
                    button.classList.add("preferences__option--selected");
                    button.title = "Remover"
                    button.innerHTML = '<span class="item__icon material-symbols-outlined">check_circle</span>' + button.innerHTML;
                }

            }
            else {
                let res = await fetchAPI(
                    "content/users/preferences/remove_personality.php",
                    "POST",
                    {
                        personality_id: button.id
                    }
                );

                if (res.status == 200) {
                    // console.log(res)
                    button.classList.remove("preferences__option--selected");
                    button.title = "Adicionar"
                    button.getElementsByTagName('span')[0].style.display = "none";
                }
            }
        })
}

function setUserPersonalData(user) {
    console.log(user);
    document.querySelector(".head__username").textContent = `${user.name} (@${user.username})`;
    document.getElementById("name").value = user.name;
    document.getElementById("date_birth").value = user.date_birth;
    document.getElementById("contact_email").value = user.contact_email;
    document.getElementById("contact_phone").value = user.contact_phone;
    document.querySelector(".head__pfp").style.backgroundImage = `url("${user.pfp_url}")`;
}

renderContent();
