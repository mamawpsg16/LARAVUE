export const  getCookie = function(cookieName) {
  const cookies = document.cookie.split(';');
    for (let i = 0; i < cookies.length; i++) {
      const cookie = cookies[i].trim();
      if (cookie.startsWith(cookieName + '=')) {
        return cookie.substring(cookieName.length + 1);
      }
    }
    return null;
}

export const  setCookie = function(cookieName, cookieValue) {
  const expirationDate = new Date();
  expirationDate.setDate(expirationDate.getDate() + 1); // Set expiration date to one day in the future

  const cookie = `${encodeURIComponent(cookieName)}=${encodeURIComponent(cookieValue)}; expires=${expirationDate.toUTCString()}; path=/`;
  document.cookie = cookie;
}

export const deleteCookie = function(cookieName) {
  document.cookie = `${cookieName}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
}

  