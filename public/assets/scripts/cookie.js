export function setCookie(cookie_name, cookie_value, expiration_days) {
    const date = new Date();
    date.setTime(date.getTime() + expiration_days * 24 * 60 * 60 * 1000);
    let expires = `expires=${date.toUTCString()}`;
    document.cookie = `${cookie_name}=${cookie_value}; ${expires}; path=/;`;
}

export function getCookie(cookie_name) {
    let name = cookie_name + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(";");
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == " ") {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

export function deleteCookie(cookie_name) {
    document.cookie = `${cookie_name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
}

export function hasCookieSet(cookie_name) {
    return getCookie(cookie_name) != "";
}
