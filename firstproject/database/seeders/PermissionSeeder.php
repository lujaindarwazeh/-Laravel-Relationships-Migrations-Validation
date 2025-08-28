<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $studentRole = Role::create(['name' => 'student']);
        $userRole = Role::create(['name' => 'user']);

        $viewstudent = Permission::create(['name' => 'view student']);
        $createstudent = Permission::create(['name' => 'create student']);

        $adminRole->givePermissionTo([
            'view student',
            'create student',
        ]);
        




        //
    }
}
