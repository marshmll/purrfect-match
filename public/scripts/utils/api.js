import { getCookie } from "./cookie.js";

export async function fetchAPI(endpoint, method = "POST", body = {}, headers = null) {
    headers = headers || new Headers({
        accept: "application/json",
        "Content-Type": "application/json",
        Authorization: `Bearer ${getCookie("token")}`,
    });

    let data = await fetch(`http://localhost:8000/api/${endpoint}`, {
        method: method,
        headers: headers,
        body: typeof(body) == "object" ?  JSON.stringify(body) : body,
    }).then(async (res) => {
        const data = {
            status: res.status,
            data: await res.json() || null,
        };

        return data;
    }).catch((error) => {
        console.error(`[api::fetchAPI]: Um erro ocorreu na requisição: ${error}`);
        throw new Error(error);
    })

    return data;
}