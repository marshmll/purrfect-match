import { fetchAPI } from "../utils/api.js";
import { setCookie } from "../utils/cookie.js";
import { toMySQLDatetime } from "../utils/mySQLDatetime.js";

// Selectors for form and feedback elements
const rescueForm = document.getElementById("rescue-form");
// const feedbackSpan = document.querySelector(".register__invalid");

// Handles form submission
rescueForm.addEventListener("submit", async (e) => {
    e.preventDefault(); // Prevent default form submission

    // Gather form data and add registration datetime
    const formData = new FormData(rescueForm);
    formData.append("datetime_register", toMySQLDatetime(new Date()));
    console.log(formData.data);
    alert(formData.values())

    // Convert form data to URL-encoded string
    const formURLEncoded = new URLSearchParams(formData).toString();

    // Set headers for the request
    const headers = new Headers({
        accept: "application/json",
        "Content-Type": "application/x-www-form-urlencoded",
    });

    // Make the API call to rescue
    const res = await fetchAPI("rescue.php", "POST", formURLEncoded, headers);

    // Handle response
    if (res.status !== 201) {
        feedbackSpan.textContent = res.data.detail; // Show error message
        return false;
    }

    document.location.replace("http://localhost:8000/pages/fyp.html");
});
