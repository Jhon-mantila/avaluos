<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Clientes>
 */
class ClientesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Asegurarse de que la carpeta "storage/app/public/logos" existe
        if (!Storage::exists('public/logos')) {
            Storage::makeDirectory('public/logos');
        }

        // Obtener archivos de la carpeta "public/logos"
        $files = Storage::files('public/logos');
        //dd($files); // Esto imprimirá el array de archivos y detendrá la ejecución.
        Log::info('Archivos en public/logos:', $files);
        return [
            //
            'id' =>  (string) Uuid::uuid4(),
            'nombre' => fake()->name(),
            'tipo_documento' => fake()->randomElement(['Nit', 'CC', 'CE', 'Pasaporte']), // Selecciona un elemento aleatorio de la lista
            'documento' => fake()->unique()->randomNumber(9), // Genera un número aleatorio de 9 dígitos
            //'logo' => fake()->imageUrl(200, 200, 'business'), // Imagen de 200x200 píxeles de la categoría "business"
            'logo' => !empty($files) ? Storage::url($files[array_rand($files)]) : null,
            'email' => $this->faker->unique()->safeEmail, // Genera un correo electrónico aleatorio
            'telefono' => $this->faker->phoneNumber, // Genera un número de teléfono aleatorio
            'direccion' => $this->faker->address, // Genera una dirección aleatoria
            'ciudad' => $this->faker->city, // Genera una ciudad aleatoria
        ];
    }
}
