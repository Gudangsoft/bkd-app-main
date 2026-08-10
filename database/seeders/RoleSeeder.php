<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $role1 = Role::create(['name' => 'admin']);
        // $role2 = Role::create(['name' => 'asesor']);
        // $role2 = Role::create(['name' => 'operator']);
        // $role3 = Role::create(['name' => 'guest']);

        // $user = User::find(1);
        // $user->remember_token = Str::random(10);
        // $user->save();
        // $user->assignRole('admin');


        // $users = User::where('id', '>', 1)->get();
        // $role = array("admin", "operator", "guest");
        // $random_keys = array_rand($role, 2);

        // foreach ($users as $key => $value) {

        //     $user = User::find($key);
        //     $user->remember_token = Str::random(10);
        //     $user->save();
        //     $user->assignRole($role[$random_keys[2]]);

        // }
    }
}
