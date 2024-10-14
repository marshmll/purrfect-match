import { hasCookieSet } from "../utils/cookie.js";
import { fetchAPI } from "../utils/api.js";

// Verifica o cookie de autenticação
if (!hasCookieSet("token")) {
    window.location.replace("http://localhost:8000/pages/login.html");
}

async function renderChatsPage() {
    const res = await fetchAPI("/content/user/chats/contacts.php");

    if (res.status !== 200) {
        window.location.replace("http://localhost:8000/pages/fyp.html");
    } else {
        const contacts = res.data;
        console.log(contacts);
        renderChatContacts(contacts);
    }
}

function renderChatContacts(contacts = []) {
    const contactsContainer = document.querySelector(".contacts__list");

    let contactItems = contacts.map((contact) => {
        return`
        <li class="contacts__item" id="${contact.sender_id}-${contact.receiver_id}">
            <div class="contacts__pfp"
                style="background-image: url('${contact.pfp_url}');">
            </div>
            <div class="contacts__text">
                <h4 class="contacts__name">${contact.name}</h4>
                <p class="contacts__last">
                    <span class="item__icon material-symbols-outlined ${contact.last_message_status}">${contact.last_message_status == "seen" ? "done_all" : "check"}</span>
                    ${contact.last_message_content}
                </p>
                <span class="contacts__datetime">${new Date(contact.last_message_datetime).toLocaleString()}</span>
            </div>
        </li>`
    }).join("");

    contactsContainer.innerHTML = contactItems;
}

function renderChatMessages() {

}

renderChatsPage();