import { useEffect, useState } from 'react';
import { getBooks, deleteBook, createBook, updateBook } from '../api';

export default function BookManager() {
    const [books, setBooks] = useState([]);
    const [form, setForm] = useState({ title: '', author: '', isbn: '', available: true });
    const [editId, setEditId] = useState(null);
    const [error, setError] = useState(null);

    // Cargar libros
    useEffect(() => {
        loadBooks();
    }, []);

    const loadBooks = () => {
        getBooks()
            .then(setBooks)
            .catch(err => setError(err.message));
    };

    // Eliminar un libro
    const handleDelete = async (id) => {
        if (!confirm('¿Eliminar este libro?')) return;
        await deleteBook(id);
        loadBooks();
    };

    // Crear o editar un libro
    const handleSubmit = async (e) => {
        e.preventDefault();
        setError(null);

        // Validar ISBN único (excepto si se edita el mismo libro)
        const isbnExists = books.some(b => b.isbn === form.isbn && b.id !== editId);
        if (isbnExists) {
            setError('Este ISBN ya está registrado en otro libro.');
            return;
        }

        try {
            console.log('editId:', editId, typeof editId);
            if (editId !== null && editId !== undefined) {
                console.log('Haciendo updateBook con ID:', editId);
                await updateBook(editId, form);
            } else {
                // SI no, hacemos POST /books
                await createBook(form);
            }

            // Limpiar estado y recargar tabla
            setForm({ title: '', author: '', isbn: '', available: true });
            setEditId(null);
            await loadBooks();
        } catch (err) {
            setError(err.message || 'Error al guardar el libro');
        }
    };

    const handleEdit = (book) => {
        // Forzar el available como booleano
        setForm({ ...book, available: Boolean(book.available) });
        setEditId(book.id);
    };

    return (
        <div className="p-6 space-y-6">
            <h2 className="text-2xl font-bold">📚 Gestión de libros</h2>

            {/* Formulario */}
            <form onSubmit={handleSubmit} className="space-y-4 p-4 border rounded shadow-md max-w-lg">
                <h3 className="text-xl font-semibold">{editId ? 'Editar libro' : 'Nuevo libro'}</h3>

                <input type="text" name="title" placeholder="Título" required
                    className="input" value={form.title}
                    onChange={e => setForm({ ...form, title: e.target.value })} />

                <input type="text" name="author" placeholder="Autor" required
                    className="input" value={form.author}
                    onChange={e => setForm({ ...form, author: e.target.value })} />

                <input type="text" name="isbn" placeholder="ISBN" required
                    className="input" value={form.isbn}
                    onChange={e => setForm({ ...form, isbn: e.target.value })} />

                <label className="flex items-center space-x-2">
                    <input type="checkbox" checked={form.available}
                        onChange={e => setForm({ ...form, available: e.target.checked })} />
                    <span>Disponible</span>
                </label>

                <div className="flex gap-2">
                    <button className="btn btn-blue" type="submit">
                        {editId ? 'Actualizar' : 'Crear'}
                    </button>
                    {editId && (
                        <button type="button" onClick={() => {
                            setEditId(null);
                            setForm({ title: '', author: '', isbn: '', available: true });
                        }} className="btn btn-gray">Cancelar</button>
                    )}
                </div>

                {error && <p className="text-red-500">{error}</p>}
            </form>

            {/* Tabla de libros */}
            <table className="min-w-full border text-sm">
                <thead className="bg-gray-100 text-left">
                    <tr>
                        <th className="p-2">Título</th>
                        <th className="p-2">Autor</th>
                        <th className="p-2">ISBN</th>
                        <th className="p-2">Disponible</th>
                        <th className="p-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    {books.map(book => (
                        <tr key={book.id} className="border-t">
                            <td className="p-2">{book.title}</td>
                            <td className="p-2">{book.author}</td>
                            <td className="p-2">{book.isbn}</td>
                            <td className="p-2">{book.available ? 'Sí' : 'No'}</td>
                            <td className="p-2 space-x-2">
                                <button onClick={() => handleEdit(book)} className="text-blue-600 hover:underline">Editar</button>
                                <button onClick={() => handleDelete(book.id)} className="text-red-600 hover:underline">Eliminar</button>
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
}
