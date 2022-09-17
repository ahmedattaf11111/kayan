export default (value) => {
    if (typeof value === "undefined" || value === null || value === "") {
        return true;
    }
    return /^01[0125][0-9]{8}$/.test(
        value
    );
} 