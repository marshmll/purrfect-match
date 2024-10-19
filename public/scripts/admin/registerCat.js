import { hasCookieSet } from "../utils/cookie.js";
import { fetchAPI } from "../utils/api.js";

// Verifica o cookie de autenticação
if (!hasCookieSet("token")) {
    window.location.replace("http://localhost:8000/pages/login.html");
}

const picturePreviewContainer = document.querySelector(".registration__picture");
const refreshBtn = document.querySelector(".registration__picture-refresh");
const pictureInput = document.getElementById("picture_url");
const form = document.querySelector(".form");

refreshBtn.addEventListener("click", updatePicturePreview);
form.addEventListener("submit", handleFormSubmit);


async function renderRegisterCatPage() {
    try {
        const [personalities, vaccines, diseases] = await Promise.all([
            fetchAPI("content/personalities/all.php"),
            fetchAPI("content/vaccines/all.php"),
            fetchAPI("content/diseases/all.php"),
        ]);

        handleAPIError(personalities, "personalities");
        handleAPIError(vaccines, "vaccines");
        handleAPIError(diseases, "diseases");

        populateFormOptions(personalities.data, "personalities", "personality");
        populateFormOptions(vaccines.data, "vaccines", "vaccine");
        populateFormOptions(diseases.data, "diseases", "disease");

    } catch (error) {
        alert("Ocorreu um erro ao carregar a página: " + error.message);
    }
}

function updatePicturePreview(event) {
    event.preventDefault();
    const defaultImage = "https://i.pinimg.com/736x/7f/16/a2/7f16a2ed1969e8c64b32801f9c48a066.jpg";
    picturePreviewContainer.style.backgroundImage = `url('${pictureInput.value || defaultImage}')`;
}

async function handleFormSubmit(event) {
    event.preventDefault();
    const submitButton = document.querySelector(".form__btn--submit");
    submitButton.innerHTML = '<span class="loader"></span>';
    submitButton.setAttribute("disabled", true);
    submitButton.style.backgroundColor = "black";
    await registerCat();
}

async function registerCat() {
    const formData = new FormData(form);
    const formObject = Object.fromEntries(formData.entries());

    const catData = {
        name: formObject.name,
        age: formObject.age,
        sex: formObject.sex,
        physical_description: formObject.physical_description,
        personalities: extractSelectedOptions(formObject, "personality_"),
        vaccines: extractSelectedOptions(formObject, "vaccine_"),
        diseases: extractSelectedOptions(formObject, "disease_"),
        picture_url: formObject.picture_url || null,
    };

    console.log(catData);

    try {
        const res = await fetchAPI("content/cats/register.php", "POST", catData);
        handleAPIError(res, "registration");

        alert("Gato registrado com sucesso!");
        window.location.replace("http://localhost:8000/pages/admin");

    } catch (error) {
        alert("Ocorreu um erro ao tentar criar o registro: " + error.message);
    }
}

function handleAPIError(response, context) {
    if (response.status !== 200) {
        throw new Error(`Erro ao carregar ${context}: ${response.data.detail}`);
    }
}

function populateFormOptions(data, elementId, namePrefix) {
    const container = document.getElementById(elementId);
    container.innerHTML = data.map((item, index) =>
        `<div class="form__checkbox">
            <label class="label" for="${namePrefix}_${index}">${item.name}</label>
            <input class="input" type="checkbox" name="${namePrefix}_${index}" value="${item.id}">
        </div>`
    ).join('');
}

function extractSelectedOptions(formObject, prefix) {
    return Object.entries(formObject)
        .filter(([key]) => key.startsWith(prefix))
        .map(([_, value]) => parseInt(value));
}

renderRegisterCatPage();