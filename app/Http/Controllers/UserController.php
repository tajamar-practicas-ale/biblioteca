<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Mostrar todos los usuarios
    public function index()
    {
        // Obtenemos todos los registros de usuarios
        $users = User::all();

        // Devuelve los usuarios (en Blade sería una vista, en API puedes usar JSON)
        return response()->json($users);
    }

    // Mostrar formulario de creación (no se usará con React, pero lo dejamos por completitud)
    public function create()
    {
        // Normalmente retorna la vista del formulario
        return response()->json(['message' => 'Formulario de creación no necesario con React.']);
    }

    // Almacenar un nuevo usuario
    public function store(Request $request)
    {
        // Validar los datos de entrada
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);

        // Crear el usuario con los datos validados
        $user = User::create($validated);

        // Responder con el usuario creado
        return response()->json($user, 201); // 201 = Created
    }

    // Mostrar un usuario específico
    public function show(User $user)
    {
        // Retornar el usuario como JSON
        return response()->json($user);
    }

    // Mostrar formulario de edición (no se usará con React)
    public function edit(User $user)
    {
        return response()->json(['message' => 'Formulario de edición no necesario con React.']);
    }

    // Actualizar un usuario existente
    public function update(Request $request, User $user)
    {
        // Validar los datos, ignorando el email actual en la regla de único
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        // Actualizar los datos del usuario
        $user->update($validated);

        // Devolver el usuario actualizado
        return response()->json($user);
    }

    // Eliminar un usuario
    public function destroy(User $user)
    {
        // Eliminar el usuario de la base de datos
        $user->delete();

        // Respuesta con mensaje
        return response()->json(['message' => 'Usuario eliminado correctamente.']);
    }
}
