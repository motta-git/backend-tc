<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ServerController extends Controller
{
    /**
     * Muestra un listado de todos los servidores.
     */
    public function index()
    {
        return Server::orderBy('sort_order', 'asc')->get();
    }

    /**
     * Guarda un nuevo servidor en la base de datos.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'host' => 'required|string|max:255',
            'ip' => 'required|ipv4',
            'description' => 'required|string|max:200',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $path = $this->resizeAndStoreImage($request->file('image'));

        $server = Server::create([
            'host' => $validatedData['host'],
            'ip' => $validatedData['ip'],
            'description' => $validatedData['description'],
            'image_path' => $path,
        ]);

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
        $validatedData = $request->validate([
            'host' => 'sometimes|required|string|max:255',
            'ip' => 'sometimes|required|ipv4',
            'description' => 'sometimes|required|string|max:200',
            'image' => 'sometimes|nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete the old image
            if ($server->image_path) {
                Storage::disk('public')->delete($server->image_path);
            }
            // Resize and store the new one
            $validatedData['image_path'] = $this->resizeAndStoreImage($request->file('image'));
        }

        $server->update($validatedData);
        return response()->json($server);
    }
    private function resizeAndStoreImage(UploadedFile $file): string
    {
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
        $path = 'servers/' . $fileName;

        $imageInfo = \getimagesize($file->getRealPath()); // Add backslash
        $sourceWidth = $imageInfo[0];
        $sourceHeight = $imageInfo[1];

        switch ($imageInfo['mime']) {
            case 'image/jpeg':
                $sourceImage = \imagecreatefromjpeg($file->getRealPath()); // Add backslash
                break;
            case 'image/png':
                $sourceImage = \imagecreatefrompng($file->getRealPath()); // Add backslash
                break;
            case 'image/gif':
                $sourceImage = \imagecreatefromgif($file->getRealPath()); // Add backslash
                break;
            default:
                return '';
        }

        $targetImage = \imagecreatetruecolor(300, 300); // Add backslash
        \imagecopyresampled($targetImage, $sourceImage, 0, 0, 0, 0, 300, 300, $sourceWidth, $sourceHeight); // Add backslash

        $tempPath = \tempnam(\sys_get_temp_dir(), 'resized-image'); // Add backslashes
        \imagepng($targetImage, $tempPath); // Add backslash

        Storage::disk('public')->put($path, \file_get_contents($tempPath)); // Add backslash

        \imagedestroy($sourceImage); // Add backslash
        \imagedestroy($targetImage); // Add backslash
        \unlink($tempPath); // Add backslash

        return $path;
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
    public function updateOrder(Request $request)
    {
        $request->validate([
            'serverIds' => 'required|array'
        ]);

        foreach ($request->serverIds as $index => $serverId) {
            Server::where('id', $serverId)->update(['sort_order' => $index]);
        }

        return response()->json(['status' => 'success']);
    }
}