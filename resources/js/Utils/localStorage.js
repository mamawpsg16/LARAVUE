export const setItem = function (key, value) {
    localStorage.setItem(key, JSON.stringify(value));
};

export const getItem = function (key) {
    const item = localStorage.getItem(key);
    return item ? JSON.parse(item) : null;
};
export const removeItem = function (key) {
    localStorage.removeItem(key);
};
