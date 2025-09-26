<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Server extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'host',
        'ip',
        'description',
        'image_path',
    ];

    /**
     * Un "accesor" para obtener la URL completa de la imagen.
     * Se añadirá automáticamente a las respuestas JSON.
     *
     * @var array
     */
    protected $appends = ['image_url'];

    /**
     * Obtiene la URL completa de la imagen del servidor.
     *
     * @return string|null
     */
    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return Storage::disk('public')->url($this->image_path);
        }
        return null;
    }
}