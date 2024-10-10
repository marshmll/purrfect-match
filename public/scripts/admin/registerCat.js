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

async function registerCat() {
    const formData = new FormData(form);
    const formObject = Object.fromEntries(formData.entries());

    let personalities = [];
    let vaccines = [];

    // Filter personalities and vaccines into an array.
    for (const [key, value] of Object.entries(formObject)) {
        if (key.includes("personality_")) personalities.push(parseInt(value));
        if (key.includes("vaccine_")) vaccines.push(parseInt(value));
    }

    const catData = {
        name: formObject.name,
        age: formObject.age,
        sex: formObject.sex,
        physical_description: formObject.physical_description,
        personalities: personalities,
        vaccines: vaccines,
        picture_url: formObject.picture_url || null,
    };

    let res = await fetchAPI("content/cats/register.php", "POST", catData);

    if (res.status != 200) {
        alert("Ocorreu um erro ao tentar criar o registro: " + res.data.detail);
        return;
    }

    console.log(res.data);
    alert("Gato registrado com sucesso!");
}