import { hasCookieSet, getCookie } from "../utils/cookie.js";
import { fetchAPI } from "../utils/api.js";

// Verifica o cookie de autenticação
if (!hasCookieSet("token")) {
    window.location.replace("http://localhost:8000/pages/login.html");
}

async function renderChatsPage() {
    await renderChatContacts();
}

async function renderChatContacts() {
    const res = await fetchAPI("/content/user/chats/contacts.php");

    if (res.status !== 200) {
        window.location.replace("http://localhost:8000/pages/fyp.html");
    }

    const contacts = res.data;
    console.log(contacts);

    const contactsContainer = document.querySelector(".contacts__list");

    let contactItems = contacts.map((contact) => {
        return `
        <li class="contacts__item" id="${contact.id}">
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

    document.querySelectorAll(".contacts__item").forEach((contact) => contact.addEventListener("click", async (e) => {
        e.preventDefault();
        const contactId = e.currentTarget.id;

        document.querySelectorAll(".contacts__item").forEach((contact) => contact.classList.remove("contacts__item--active"));
        e.currentTarget.classList.add("contacts__item--active");

        await renderChatMessages(contactId);
        await renderChatContacts();
    }))
}

async function renderChatMessages(contact_id) {
    const body = {
        contact_id
    };

    const visualization = await fetchAPI("/content/user/chats/set_all_seen.php", "POST", body);

    if (visualization.status !== 200) {
        window.location.replace("http://localhost:8000/pages/login.html");
    }

    const res = await fetchAPI("/content/user/chats/messages.php", "POST", body);

    if (res.status !== 200) {
        window.location.replace("http://localhost:8000/pages/login.html");
    } else {
        const messages = res.data;

        const messagesItems = messages.map(message => {
            return `
            <li class="message message--${message.current_user_id == message.receiver_id ? "received" : "sent"}">
                <p class="message__content">${message.content}</p>
                <span class="message__datetime">${new Date(message.sent_datetime).toLocaleString()}</span>
                ${message.sender_id == message.current_user_id ? `<span class="item__icon material-symbols-outlined message__status ${message.status}">${message.status == "seen" ? "done_all" : "check"}</span>` : ""}
            </li>
            `;
        }).join("");

        document.querySelector(".chat__messages").innerHTML = messagesItems;
    }
}

async function sendMessage() {

}

document.getElementById("content").addEventListener("keyup", (e) => {
    const stripedContent = e.currentTarget.value.trim()
    const submitButton = document.querySelector(".chat__submit");

    if (stripedContent.length != 0) {
        submitButton.classList.remove("chat__submit--disabled");
        submitButton.disabled = false;
    } else {
        submitButton.classList.add("chat__submit--disabled");
        submitButton.disabled = true;
    }
})

document.querySelector(".chat__form").addEventListener("submit", async (e) => {
    e.preventDefault();

    const contact_id = document.querySelector(".contacts__item--active").id;

    const formData = new FormData(e.currentTarget);
    formData.append("contact_id", parseInt(contact_id));
    formData.set("content", formData.get("content").toString().trim());

    const body = Object.fromEntries(formData.entries())

    console.log(body);

    const res = await fetchAPI("/content/user/chats/send.php", "POST", body);

    if (res.status !== 200)
        alert("Algo deu errado no envio da mensagem!");
    else {
        renderChatContacts()
        renderChatMessages(contact_id);
        e.currentTarget.value = "";
    }
});

renderChatsPage();