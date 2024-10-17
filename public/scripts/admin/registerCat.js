import { fetchAPI } from "../utils/api.js";

const picturePreviewContainer = document.querySelector(".registration__picture");
const refreshBtn = document.querySelector(".registration__picture-refresh");
const pictureInput = document.getElementById("picture_url");

const form = document.querySelector(".form");

refreshBtn.addEventListener("click", (e) => {
    e.preventDefault();

    console.log("aa")

    picturePreviewContainer.style.backgroundImage = `url('${pictureInput.value || "https://i.pinimg.com/736x/7f/16/a2/7f16a2ed1969e8c64b32801f9c48a066.jpg"}')`;
});

form.addEventListener("submit", async (e) => {
    e.preventDefault();

    await registerCat();
})

async function renderRegisterCatPage() {
    let personalities = await fetchAPI("content/personalities/all.php");
    let vaccines = await fetchAPI("content/vaccines/all.php");
    let diseases = await fetchAPI("content/diseases/all.php");

    if (personalities.status != 200) {
        alert("Ocorreu um erro ao carregar a página: " + personalities.data.detail);
        return;
    }
    else if (vaccines.status != 200) {
        alert("Ocorreu um erro ao carregar a página: " + vaccines.data.detail);
        return;
    }
    else if (diseases.status != 200) {
        alert("Ocorreu um erro ao carregar a página: " + diseases.data.detail);
        return;
    }

    console.log(personalities.data);
    console.log(vaccines.data);
    console.log(diseases.data);

    for (let i = 0; i < personalities.data.length; i++) {
        let personality = personalities.data[i];

        document.getElementById("personalities").innerHTML += `
            <div class="form__checkbox">
                <label class="label" for="personality_${i}">${personality.name}</label>
                <input class="input" type="checkbox" name="personality_${i}" value="${personality.id}">
            </div>
        `;
    }

    for (let i = 0; i < vaccines.data.length; i++) {
        let vaccine = vaccines.data[i];

        document.getElementById("vaccines").innerHTML += `
            <div class="form__checkbox">
                <label class="label" for="vaccine_${i}">${vaccine.name}</label>
                <input class="input" type="checkbox" name="vaccine_${i}" value="${vaccine.id}">
            </div>
        `;
    }

    for (let i = 0; i < diseases.data.length; i++) {
        let disease = diseases.data[i];

        document.getElementById("diseases").innerHTML += `
            <div class="form__checkbox">
                <label class="label" for="disease_${i}">${disease.name}</label>
                <input class="input" type="checkbox" name="disease_${i}" value="${disease.id}">
            </div>
        `;
    }
}

async function registerCat() {
    const formData = new FormData(form);
    const formObject = Object.fromEntries(formData.entries());

    let personalities = [];
    let vaccines = [];
    let diseases = [];

    // Filter personalities and vaccines into an array.
    for (const [key, value] of Object.entries(formObject)) {
        if (key.includes("personality_")) personalities.push(parseInt(value));
        if (key.includes("vaccine_")) vaccines.push(parseInt(value));
        if (key.includes("disease_")) diseases.push(parseInt(value));
    }

    const catData = {
        name: formObject.name,
        age: formObject.age,
        sex: formObject.sex,
        physical_description: formObject.physical_description,
        personalities: personalities,
        vaccines: vaccines,
        diseases: diseases,
        picture_url: formObject.picture_url || null,
    };

    console.log(catData);

    let res = await fetchAPI("content/cats/register.php", "POST", catData);

    if (res.status != 200) {
        alert("Ocorreu um erro ao tentar criar o registro: " + res.data.detail);
        return;
    }

    console.log(res.data);
    alert("Gato registrado com sucesso!");
}

renderRegisterCatPage();