<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookController extends Controller
{
    // Mostrar todos los libros
    public function index()
    {
        // Obtenemos todos los libros de la base de datos
        $books = Book::all();

        // Retornamos json para React
        return response()->json($books);
    }

    // Mostrar formulario para crear un libro nuevo
    public function create()
    {
        // Retorna la vista con el formulario de creación
        return view('books.create');
    }

    // Almacenar un libro nuevo en la base de datos
    public function store(Request $request)
    {
        // Validamos los datos recibidos del formulario
        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'author'    => 'required|string|max:255',
            'isbn'      => 'required|string|max:20|unique:books',
            'available' => 'required|boolean',
        ]);

        // Creamos un nuevo libro con los datos validados
        Book::create($validated);

        // Redirigimos al listado de libros con un mensaje
        return redirect()->route('books.index')->with('success', 'Libro creado correctamente.');
    }

    // Mostrar los detalles de un solo libro
    public function show(Book $book)
    {
        // Mostramos la vista con los detalles del libro
        return view('books.show', compact('book'));
    }

    // Mostrar formulario para editar un libro existente
    public function edit(Book $book)
    {
        // Mostramos el formulario con los datos del libro ya cargados
        return view('books.edit', compact('book'));
    }

    // Actualizar los datos del libro
    public function update(Request $request, Book $book)
    {
    	try {
        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'author'    => 'required|string|max:255',
            'isbn'      => 'required|string|max:20|unique:books,isbn,' . $book->id,
            'available' => 'required|boolean',
        ]);

        $book->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Libro actualizado correctamente',
                'book' => $book,
            ]);
        }

        	return redirect()->route('books.index')->with('success', 'Libro actualizado');
    	} catch (\Throwable $e) {
        	return response()->json(['error' => $e->getMessage()], 500);
    	}
    }

    // Eliminar un libro
    public function destroy(Book $book)
    {
        // Eliminamos el libro de la base de datos
        $book->delete();

        // Redirigimos al listado con mensaje
        return redirect()->route('books.index')->with('success', 'Libro eliminado correctamente.');
    }
}
