<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Crear permisos
            $permissions = [
                // Visitadores
                'visitadores.create', 'visitadores.show', 'visitadores.index', 'visitadores.update', 'visitadores.delete',

                // Clientes
                'clientes.create', 'clientes.show', 'clientes.index', 'clientes.update', 'clientes.delete',

                // Avalúos
                'avaluos.create', 'avaluos.show', 'avaluos.index', 'avaluos.update', 'avaluos.delete',

                // Información de Visita
                'informacion-visita.create', 'informacion-visita.show', 'informacion-visita.index', 'informacion-visita.update', 'informacion-visita.delete',

                // Plantillas
                'plantillas.create', 'plantillas.show', 'plantillas.index', 'plantillas.update', 'plantillas.delete',

                // Registros Fotográficos
                'registro-fotograficos.create', 'registro-fotograficos.update', 'registro-fotograficos.delete',
            ];

            foreach ($permissions as $permission) {
                Permission::updateOrCreate(['name' => $permission]);
            }

            // Crear roles sin duplicados
            $adminRole = Role::updateOrCreate(['name' => 'admin']);
            $visitadorRole = Role::updateOrCreate(['name' => 'visitador']);

            // Asignar permisos a los roles
            $adminRole->syncPermissions(Permission::all()); // Admin tiene todos los permisos
            $visitadorRole->syncPermissions([
                'informacion-visita.create', 'informacion-visita.show', 'informacion-visita.index', 'informacion-visita.update',
                'plantillas.create', 'plantillas.show', 'plantillas.index', 'plantillas.update',
            ]); // Visitador tiene permisos específicos

            // Asignar rol de admin a un usuario si existe
            optional(User::find(1))->assignRole('admin');

            // Asignar rol de visitador a otro usuario si existe
            //optional(User::find(2))->assignRole('visitador');
        });
    }
}