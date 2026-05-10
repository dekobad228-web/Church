export default async function makeRequest(url, data = null, method = "POST") {
    const params = {
        method: method,
        headers: {},
        body: data
    };

    try {
        const response = await fetch(url, params);
        const text = await response.text();
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${text}`);
        }
        if (!text) throw new Error('Пустой ответ сервера');
        return JSON.parse(text);
    } catch (error) {
        return {
            status: 'error',
            message: error.message
        };
    }
}