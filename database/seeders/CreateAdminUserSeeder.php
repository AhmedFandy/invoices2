<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Ahmed Fandy',
            'email' => 'ahmedfandy90@gmail.com',
            'password' => bcrypt('123456'),
            'roles_name' => ["owner"],
            'Status' => 'مفعل',
            ]);
            $role = Role::create(['name' => 'Owner']);
            $permissions = Permission::pluck('id','id')->all();
            $role->syncPermissions($permissions);
            $user->assignRole([$role->id]);
            

        // $user1 = User::create([
        //     'name' => 'Mohamed Saber',
        //     'email' => 'saber@gmail.com',
        //     'password' => bcrypt('123456'),
        //     'roles_name' => ["user"],
        //     'Status' => 'مفعل',
        //     ]);
        //     $role = Role::create(['name' => 'User']);
        //     $permissions = Permission::pluck('id','id')->with('الفواتير' , 'قائمة الفواتير' ,  'الفواتير المدفوعة')->get();
        //     $role->syncPermissions($permissions);
        //     $user1->assignRole([$role->id]);
    }
}