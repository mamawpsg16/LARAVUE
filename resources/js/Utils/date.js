export function dateFormat(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString();
}

export function humanReadable(dateString) {
    const date = new Date(dateString);
    const options = { month: "long", day: "numeric", year: "numeric" };
    return date.toLocaleDateString(undefined, options);
}
