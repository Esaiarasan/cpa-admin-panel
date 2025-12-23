// Common reusable functions
function apiFetch(url, options = {}) {
    return fetch(url, options)
        .then(response => response.json())
        .catch(error => {
            console.error("API Fetch Error:", error);
            return null;
        });
}

function showMessage(msg, type = "info") {
    // Simple alert wrapper (can replace with bootstrap/toast later)
    alert(`[${type.toUpperCase()}] ${msg}`);
}
