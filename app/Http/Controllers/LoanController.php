<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    // Lista todos los préstamos
    public function index()
    {
        return Loan::with(['user', 'book'])->get();
    }

    // Muestra un préstamo específico
    public function show($id)
    {
        return Loan::with(['user', 'book'])->findOrFail($id);
    }

    // Crea un nuevo préstamo
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'loan_date' => 'required|date',
            'return_date' => 'nullable|date|after_or_equal:loan_date',
        ]);

        $book = Book::findOrFail($validated['book_id']);

        // Verifica si el libro está disponible
        if (!$book->available) {
            return response()->json(['message' => 'El libro no está disponible para préstamo.'], 400);
        }

        // Marca el libro como no disponible
        $book->available = false;
        $book->save();

        // Crea el préstamo
        $loan = Loan::create([
            ...$validated,
            'returned' => false,
        ]);

        return response()->json($loan->load(['user', 'book']), 201);
    }

    // Actualiza un préstamo existente
    public function update(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);

        $validated = $request->validate([
            'return_date' => 'nullable|date|after_or_equal:loan_date',
            'returned' => 'boolean',
        ]);

        $loan->update($validated);

        return $loan->load(['user', 'book']);
    }

    // Elimina un préstamo
    public function destroy($id)
    {
        $loan = Loan::findOrFail($id);

        // Si el préstamo no fue devuelto, marcar el libro como disponible
        if (!$loan->returned) {
            $loan->book->available = true;
            $loan->book->save();
        }

        $loan->delete();

        return response()->json(['message' => 'Préstamo eliminado']);
    }

    // Devuelve un préstamo (nuevo endpoint)
    public function returnLoan($id)
    {
        $loan = Loan::findOrFail($id);

        // Si ya fue devuelto, no hacer nada
        if ($loan->returned) {
            return response()->json(['message' => 'Este préstamo ya fue devuelto.'], 400);
        }

        // Marca el préstamo como devuelto
        $loan->returned = true;
        $loan->return_date = now();
        $loan->save();

        // Marca el libro como disponible
        $loan->book->available = true;
        $loan->book->save();

        return response()->json(['message' => 'Libro devuelto correctamente.']);
    }
}
