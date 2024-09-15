const loginForm = document.getElementById("form");

loginForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = Object.fromEntries(new FormData(e.target));
    const formURLEncoded = new URLSearchParams(formData).toString();

    const headers = new Headers({
        accept: "application/json",
        "Content-Type": "application/x-www-form-urlencoded",
    });

    let data = await fetch("http://localhost/purrfect-match/php/auth.php", {
        method: "POST",
        headers: headers,
        body: formURLEncoded,
    }).then((res) => res.json());

    console.log(`Dados recebidos: ${JSON.stringify(data)}`);
});
