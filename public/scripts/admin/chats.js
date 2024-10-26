import { hasCookieSet } from "../utils/cookie.js";
import { fetchAPI } from "../utils/api.js";

let currentTimeoutId;
let selectedContactId;
let contacts = [];
let users = [];

// Redirect to login if authentication cookie is missing
checkAuthCookie();

async function checkAuthCookie() {
    if (!hasCookieSet("token")) {
        redirectToLogin();
    }
}

// Redirects to the login page
function redirectToLogin() {
    window.location.replace("http://localhost:8000/pages/login.html");
}

// Initializes the chat page by rendering contacts and messages
async function renderChatsPage() {
    await renderChatContacts();
    initializeFirstContact();
}

// Select and render the first contact
function initializeFirstContact() {
    const firstContact = document.querySelector(".contacts__item");
    if (firstContact) {
        firstContact.classList.add("contacts__item--active");
        selectedContactId = firstContact.id;
        renderChatMessages();
        scrollToLatestMessage();
    }
}

// Fetch and render the chat contacts
async function renderChatContacts() {
    try {
        const res = await fetchAPI("/content/users/chats/contacts.php");
        if (res.status !== 200) throw new Error("Failed to fetch contacts");
        contacts = res.data;
        updateContactsList();
    } catch (error) {
        redirectToFYP();
    }
}

// Updates the contacts list UI
function updateContactsList() {
    const contactsContainer = document.querySelector(".contacts__list");
    contactsContainer.innerHTML = contacts.map(contact => createContactItem(contact)).join("");
    addContactClickListeners();
}

// Creates HTML for a contact item
function createContactItem(contact) {
    const isActive = contact.id === selectedContactId ? "contacts__item--active" : "";
    return `
        <li class="contacts__item ${isActive}" id="${contact.id}">
            <div class="contacts__pfp" style="background-image: url('${contact.pfp_url}');"></div>
            <div class="contacts__text">
                <h4 class="contacts__name">${contact.name}</h4>
                <p class="contacts__last">
                    <span class="item__icon material-symbols-outlined ${contact.last_message_status}">
                        ${contact.last_message_status === "seen" ? "done_all" : "check"}
                    </span>
                    ${contact.last_message_content}
                </p>
                <span class="contacts__datetime">${formatDatetime(contact.last_message_datetime)}</span>
            </div>
        </li>
    `;
}

// Adds event listeners to contact items
function addContactClickListeners() {
    document.querySelectorAll(".contacts__item").forEach(contact => {
        contact.addEventListener("click", async (e) => {
            e.preventDefault();
            selectedContactId = e.currentTarget.id;
            await handleContactClick();
        });
    });
}

// Handles contact click event
async function handleContactClick() {
    updateActiveContactUI();
    clearTimeout(currentTimeoutId);
    await renderChatMessages();
}

// Updates the UI to highlight the selected contact
function updateActiveContactUI() {
    document.querySelectorAll(".contacts__item").forEach(contact => contact.classList.remove("contacts__item--active"));
    const contact = document.getElementById(selectedContactId);
    if (contact) contact.classList.add("contacts__item--active");
}

// Fetch and render chat messages for the selected contact
async function renderChatMessages() {
    try {
        const contact = getSelectedContact();
        updateChatHeader(contact);
        await markMessagesAsSeen();

        const res = await fetchAPI("/content/users/chats/messages.php", "POST", { contact_id: selectedContactId });
        if (res.status !== 200) throw new Error("Failed to fetch messages");

        displayMessages(res.data);
        autoRefreshMessages();
    } catch (error) {
        redirectToLogin();
    }
}

// Gets the selected contact from contacts or users
function getSelectedContact() {
    return contacts.find(contact => contact.id === selectedContactId) || users.find(user => user.id === selectedContactId);
}

// Updates the chat header with the selected contact's details
function updateChatHeader(contact) {
    document.querySelector(".chat__head").innerHTML = `
        <div class="chat__pfp" style="background-image: url('${contact.pfp_url}');"></div>
        <h3 class="chat__name">${contact.name}</h3>
    `;
}

// Marks all messages as seen for the selected contact
async function markMessagesAsSeen() {
    await fetchAPI("/content/users/chats/set_all_seen.php", "POST", { contact_id: selectedContactId });
}

// Displays messages in the chat window
function displayMessages(messages) {
    document.querySelector(".chat__messages").innerHTML = messages.map(createMessageItem).join("");
    scrollToLatestMessage();
}

// Creates HTML for a single message
function createMessageItem(message) {
    const isReceived = message.current_user_id === message.receiver_id ? "received" : "sent";
    const statusIcon = message.sender_id === message.current_user_id
        ? `<span class="item__icon material-symbols-outlined message__status ${message.status}">
               ${message.status === "seen" ? "done_all" : "check"}
           </span>`
        : "";
    return `
        <li class="message message--${isReceived}">
            <p class="message__content">${message.content}</p>
            <span class="message__datetime">${formatDatetime(message.sent_datetime)}</span>
            ${statusIcon}
        </li>
    `;
}

// Auto-refreshes messages every 5 seconds
function autoRefreshMessages() {
    currentTimeoutId = setTimeout(async () => {
        await renderChatContacts();
        await renderChatMessages();
    }, 5000);
}

// Renders users modal to select new contacts
async function renderUsersModal() {
    try {
        const res = await fetchAPI("/content/users/all.php");
        if (res.status !== 200) throw new Error("Failed to fetch users");

        users = res.data;
        displayUsersModal();
        addUserSelectionListeners();
        showModal();
    } catch (error) {
        redirectToFYP();
    }
}

// Displays users in the modal
function displayUsersModal() {
    const usersList = users.map(user => createUserItem(user)).join("");
    document.querySelector(".users").innerHTML = usersList;
}

// Creates HTML for a user item in the modal
function createUserItem(user) {
    return `
        <li class="users__item" id="${user.id}">
            <div class="users__pfp" style="background-image: url('${user.pfp_url}');"></div>
            <div class="users__text">
                <h4 class="users__name">${user.name} (@${user.username})</h4>
                <p class="users__datetime">Entrou em ${formatDatetime(user.datetime_register)}</p>
            </div>
        </li>
    `;
}

// Adds click listeners for user selection
function addUserSelectionListeners() {
    document.querySelectorAll(".users__item").forEach(item => {
        item.addEventListener("click", async (e) => {
            e.preventDefault();
            selectedContactId = e.currentTarget.id;
            addSelectedUserToContacts();
            closeModal();
        });
    });
}

// Adds the selected user to contacts and refreshes chat
async function addSelectedUserToContacts() {
    const user = users.find(user => user.id == selectedContactId);
    contacts.push(user);
    clearTimeout(currentTimeoutId);
    await renderChatContacts();
    await renderChatMessages();
}

// Show modal
function showModal() {
    document.querySelector(".modal").classList.remove("modal--hidden");
}

// Close modal
function closeModal() {
    document.querySelector(".modal").classList.add("modal--hidden");
}

// Scrolls to the latest message
function scrollToLatestMessage() {
    const messages = document.querySelectorAll(".message");
    if (messages.length) {
        messages[messages.length - 1].scrollIntoView();
    }
}

// Formats datetime for display
function formatDatetime(datetime) {
    return new Date(datetime).toLocaleString();
}

// Redirects to FYP page
function redirectToFYP() {
    window.location.replace("http://localhost:8000/pages/fyp.html");
}

// Event listeners
document.querySelector(".contacts__add").addEventListener("click", renderUsersModal);
document.querySelector(".container__close").addEventListener("click", closeModal);
document.getElementById("content").addEventListener("keyup", toggleSubmitButton);
document.querySelector(".chat__form").addEventListener("submit", handleMessageSubmit);

// Toggles the submit button based on input content
function toggleSubmitButton(e) {
    const inputContent = e.currentTarget.value.trim();
    const submitButton = document.querySelector(".chat__submit");
    submitButton.classList.toggle("chat__submit--disabled", inputContent.length === 0);
    submitButton.disabled = inputContent.length === 0;
}

// Handles message submission
async function handleMessageSubmit(e) {
    e.preventDefault();
    document.querySelector(".chat__submit").classList.add("chat__submit--disabled");
    const formData = new FormData(e.currentTarget);
    formData.append("contact_id", parseInt(selectedContactId));
    formData.set("content", formData.get("content").trim());

    try {
        const res = await fetchAPI("/content/users/chats/send.php", "POST", Object.fromEntries(formData.entries()));
        if (res.status !== 200) throw new Error("Message sending failed");
        document.getElementById("content").value = "";
        await renderChatContacts();
        await renderChatMessages();
        scrollToLatestMessage();
    } catch (error) {
        alert("An error occurred while sending the message!");
    }
}

// Initialize chat page
renderChatsPage();
