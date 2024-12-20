import { hasCookieSet, getCookie } from "../utils/cookie.js";
import { fetchAPI } from "../utils/api.js";

let currentTimeoutId;
let contacts = [];

// Redirects to login page if authentication cookie is not set
if (!hasCookieSet("token")) {
    redirectToLogin();
}

// Redirects to login page
function redirectToLogin() {
    window.location.replace("http://localhost:8000/pages/login.html");
}

// Initializes the chat page by rendering contacts and messages
async function renderChatsPage() {
    await renderChatContacts();

    const firstContact = document.querySelector(".contacts__item");
    if (firstContact) {
        firstContact.classList.add("contacts__item--active");
        await renderChatMessages(firstContact.id);
        scrollToLatestMessage();
    }
}

// Renders the list of chat contacts
async function renderChatContacts(contactId = "") {
    try {
        const res = await fetchAPI("/content/users/chats/contacts.php");

        if (res.status !== 200) throw new Error("Failed to fetch contacts");

        contacts = res.data;
        const contactsContainer = document.querySelector(".contacts__list");

        contactsContainer.innerHTML = contacts.map(contact => createContactItem(contact, contactId)).join("");

        // Add event listeners to contact items
        document.querySelectorAll(".contacts__item").forEach(contact => {
            contact.addEventListener("click", async (e) => {
                e.preventDefault();
                const selectedContactId = e.currentTarget.id;
                await handleContactClick(selectedContactId);
            });
        });
    } catch (error) {
        redirectToLogin();
    }
}

// Creates HTML for a single contact item
function createContactItem(contact, activeContactId) {
    return `
        <li class="contacts__item ${contact.id === activeContactId ? "contacts__item--active" : ""}" id="${contact.id}">
            <div class="contacts__pfp" style="background-image: url('${contact.pfp_url}');"></div>
            <div class="contacts__text">
                <h4 class="contacts__name">${contact.name}</h4>
                <p class="contacts__last">
                    <span class="item__icon material-symbols-outlined ${contact.last_message_status}">
                        ${contact.last_message_status === "seen" ? "done_all" : "check"}
                    </span>
                    ${contact.last_message_content}
                </p>
                <span class="contacts__datetime">${new Date(contact.last_message_datetime).toLocaleString()}</span>
            </div>
        </li>
    `;
}

// Handles click on a contact, updates UI, and fetches messages
async function handleContactClick(contactId) {
    document.querySelectorAll(".contacts__item").forEach(contact => {
        contact.classList.remove("contacts__item--active");
    });

    document.getElementById(contactId).classList.add("contacts__item--active");

    clearTimeout(currentTimeoutId);

    await renderChatMessages(contactId);
}

// Renders messages for a selected contact
async function renderChatMessages(contactId) {
    const body = { contact_id: contactId };

    const contact = contacts.find(contact => contact.id == contactId);

    updateChatHeader(contact);

    // Mark all messages as seen
    await fetchAPI("/content/users/chats/set_all_seen.php", "POST", body);

    try {
        const res = await fetchAPI("/content/users/chats/messages.php", "POST", body);

        if (res.status !== 200) throw new Error("Failed to fetch messages");

        const messages = res.data;
        displayMessages(messages);

        // Set a timeout to refresh messages
        currentTimeoutId = setTimeout(async () => {
            await renderChatContacts(contactId);
            await renderChatMessages(contactId);
        }, 5000);
    } catch (error) {
        redirectToLogin();
    }
}

// Updates the chat header with the selected contact's details
function updateChatHeader(contact) {
    document.querySelector(".chat__head").innerHTML = `
        <div class="chat__pfp" style="background-image: url('${contact.pfp_url}');"></div>
        <h3 class="chat__name">${contact.name}</h3>
    `;
}

// Displays the chat messages
function displayMessages(messages) {
    document.querySelector(".chat__messages").innerHTML = messages.map(createMessageItem).join("");
    scrollToLatestMessage();
}

// Creates HTML for a single message item
function createMessageItem(message) {
    return `
        <li class="message message--${message.current_user_id === message.receiver_id ? "received" : "sent"}">
            <p class="message__content">${message.content}</p>
            <span class="message__datetime">${new Date(message.sent_datetime).toLocaleString()}</span>
            ${message.sender_id === message.current_user_id ? `<span class="item__icon material-symbols-outlined message__status ${message.status}">${message.status === "seen" ? "done_all" : "check"}</span>` : ""}
        </li>
    `;
}

// Scrolls to the latest message in the chat
function scrollToLatestMessage() {
    const allMessages = document.querySelectorAll(".message");
    if (allMessages.length) {
        allMessages[allMessages.length - 1].scrollIntoView();
    }
}

// Enables or disables the submit button based on input content
document.getElementById("content").addEventListener("keyup", (e) => {
    const inputContent = e.currentTarget.value.trim();
    const submitButton = document.querySelector(".chat__submit");
    submitButton.classList.toggle("chat__submit--disabled", inputContent.length === 0);
    submitButton.disabled = inputContent.length === 0;
});

// Handles message submission
document.querySelector(".chat__form").addEventListener("submit", async (e) => {
    e.preventDefault();

    document.querySelector(".chat__submit").classList.add("chat__submit--disabled");

    const contactId = document.querySelector(".contacts__item--active").id;
    const formData = new FormData(e.currentTarget);
    formData.append("contact_id", parseInt(contactId));
    formData.set("content", formData.get("content").trim());

    const body = Object.fromEntries(formData.entries());

    try {
        const res = await fetchAPI("/content/users/chats/send.php", "POST", body);

        if (res.status !== 200) throw new Error("Message sending failed");

        document.getElementById("content").value = "";
        await renderChatContacts(contactId);
        await renderChatMessages(contactId);
        scrollToLatestMessage();
    } catch (error) {
        alert("An error occurred while sending the message!");
    }
});

// Initial call to render the chat page
renderChatsPage();
