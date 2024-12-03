<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Crear roles
        $admin = Role::create(['name' => 'admin']);
        $supervisor = Role::create(['name' => 'supervisor']);
        $worker = Role::create(['name' => 'worker']);

        // Crear permisos
        $permissions = [
            'manage-users',             // Gestionar usuarios
            'view-categories',          // Ver categorías
            'manage-categories',        // Gestionar categorías (crear, editar, eliminar)
            'view-products',            // Ver productos
            'manage-products',          // Gestionar productos (crear, editar, eliminar)
            'view-customers',           // Ver clientes
            'manage-customers',         // Gestionar clientes
            'view-suppliers',           // Ver proveedores
            'manage-suppliers',         // Gestionar proveedores
            'view-stock',               // Ver stock
            'manage-stock',             // Gestionar entradas de inventario
            'create-stock-entry',       // Crear ingresos de inventario
            'view-sales',               // Ver ventas
            'manage-sales',             // Gestionar ventas
            'create-sales',             // Crear ventas
            'view-expenses',            // Ver gastos
            'manage-expenses',          // Gestionar gastos
            'create-expenses',          // Crear gastos
            'generate-reports',         // Generar reportes generales
            'generate-personal-reports' // Generar reportes personales
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Asignar permisos al rol de administrador (acceso completo)
        $admin->givePermissionTo(Permission::all());

        // Asignar permisos al rol de supervisor (sin permisos para eliminar)
        $supervisor->givePermissionTo([
            'view-categories',
            'manage-categories',
            'view-products',
            'manage-products',
            'view-customers',
            'manage-customers',
            'view-suppliers',
            'manage-suppliers',
            'view-stock',
            'manage-stock',
            'view-sales',
            'manage-sales',
            'view-expenses',
            'manage-expenses',
            'generate-reports',
        ]);

        // Asignar permisos al rol de trabajador (solo operaciones específicas)
        $worker->givePermissionTo([
            'view-products',
            'view-categories',
            'view-stock',
            'create-stock-entry',       // Permiso para crear ingresos de stock
            'view-sales',
            'create-sales',             // Permiso para crear ventas
            'manage-sales',
            'view-expenses',
            'create-expenses',          // Permiso para crear gastos
            'manage-expenses',
            'generate-personal-reports', // Permiso para reportes personales
        ]);
    }
}
