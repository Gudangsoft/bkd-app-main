<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Matches the role options offered on the user create/edit forms
        // (resources/views/admin/users/index.blade.php and modal.blade.php).
        foreach (['admin', 'dosen', 'asesor', 'operator', 'guest'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }
    }
}
