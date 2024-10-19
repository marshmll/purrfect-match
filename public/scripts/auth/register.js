import { fetchAPI } from "../utils/api.js";
import { setCookie } from "../utils/cookie.js";

// Selectors for form and feedback elements
const registerForm = document.getElementById("form");
const feedbackSpan = document.querySelector(".register__invalid");

// Handles form submission
registerForm.addEventListener("submit", async (e) => {
    e.preventDefault(); // Prevent default form submission

    // Gather form data and add registration datetime
    const formData = new FormData(registerForm);

    // Convert form data to URL-encoded string
    const formURLEncoded = new URLSearchParams(formData).toString();

    // Set headers for the request
    const headers = new Headers({
        accept: "application/json",
        "Content-Type": "application/x-www-form-urlencoded",
    });

    // Make the API call to register
    const res = await fetchAPI("register.php", "POST", formURLEncoded, headers);

    // Handle response
    if (res.status !== 201) {
        feedbackSpan.textContent = res.data.detail; // Show error message
        return false;
    }

    // Set authentication cookie and redirect to the main page
    setCookie("token", res.data.access_token, 7);
    document.location.replace("http://localhost:8000/pages/fyp.html");
});
