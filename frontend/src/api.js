const API_URL = import.meta.env.VITE_API_URL;

export async function getBooks() {
    const res = await fetch(`${API_URL}/books`);
    if (!res.ok) throw new Error('Error cargando libros');
    return await res.json();
}

export async function deleteBook(id) {
    const res = await fetch(`${API_URL}/books/${id}`, {
        method: 'DELETE',
    });
    if (!res.ok) throw new Error('Error al eliminar libro');
}

export async function createBook(data) {
    const res = await fetch(`${API_URL}/books`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', },
        body: JSON.stringify(data),
    });
    if (!res.ok) throw new Error('Error al crear libro');
    return await res.json();
}

export async function updateBook(id, data) {
    console.log("Haciendo PUT a:", `${API_URL}/books/${id}`);
    const res = await fetch(`${API_URL}/books/${id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data),
    });
    if (!res.ok) throw new Error('Error al actualizar libro');
    return await res.json();
}