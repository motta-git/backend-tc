<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServerController extends Controller
{
    /**
     * Muestra un listado de todos los servidores.
     */
    public function index()
    {
        return Server::all();
    }

    /**
     * Guarda un nuevo servidor en la base de datos.
     */
    public function store(Request $request)
    {
        // 1. Validar los datos de entrada
        $validatedData = $request->validate([
            'host' => 'required|string|max:255',
            'ip' => 'required|ip',
            'description' => 'required|string|max:200',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048|dimensions:width=300,height=300'
        ]);

        // 2. Manejar la subida de la imagen
        // La imagen se guardará en `storage/app/public/servers`
        $path = $request->file('image')->store('servers', 'public');

        // 3. Crear el registro en la base de datos
        $server = Server::create([
            'host' => $validatedData['host'],
            'ip' => $validatedData['ip'],
            'description' => $validatedData['description'],
            'image_path' => $path
        ]);

        // 4. Devolver el recurso creado con un código de estado 201
        return response()->json($server, 201);
    }

    /**
     * Muestra un servidor específico.
     */
    public function show(Server $server)
    {
        // Laravel encuentra el servidor por su ID automáticamente
        return $server;
    }

    /**
     * Actualiza un servidor existente.
     */
    public function update(Request $request, Server $server)
    {
        // 'sometimes' significa que solo se valida si el campo está presente en la petición
        $validatedData = $request->validate([
            'host' => 'sometimes|required|string|max:255',
            'ip' => 'sometimes|required|ip',
            'description' => 'sometimes|required|string|max:200',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048|dimensions:width=300,height=300'
        ]);

        // Si se sube una nueva imagen
        if ($request->hasFile('image')) {
            // Borrar la imagen antigua para no dejar archivos basura
            if ($server->image_path) {
                Storage::disk('public')->delete($server->image_path);
            }
            // Guardar la nueva imagen y actualizar la ruta
            $validatedData['image_path'] = $request->file('image')->store('servers', 'public');
        }

        // Actualizar el modelo con los datos validados
        $server->update($validatedData);

        return response()->json($server);
    }

    /**
     * Elimina un servidor.
     */
    public function destroy(Server $server)
    {
        // Borrar la imagen asociada del almacenamiento
        if ($server->image_path) {
            Storage::disk('public')->delete($server->image_path);
        }

        // Borrar el registro de la base de datos
        $server->delete();

        // Devolver una respuesta vacía con código 204 (éxito, sin contenido)
        return response()->noContent();
    }
}