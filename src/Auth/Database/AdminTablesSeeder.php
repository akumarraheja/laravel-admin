<?php

namespace Encore\Admin\Auth\Database;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create a user.
        Administrator::truncate();
        Administrator::insert([
            [
            'username' => 'admin',
            'password' => Hash::make('admin'),
            'name'     => 'Admin',
            'avatar'   => 'images/users-vector-icon-png_260862.jpg',
            'email'    => 'akumarraheja@gmail.com'
            ]
        ]);

        // create a role.
        Role::truncate();
        Role::create([
            'name' => 'Administrator',
            'slug' => 'administrator',
        ]);

        // add role to user.
        Administrator::first()->roles()->save(Role::first());

        //create a permission
        Permission::truncate();
        Permission::insert([
            [
                'name'        => 'All permission',
                'slug'        => '*',
                'http_method' => '',
                'http_path'   => '*',
            ],
            [
                'name'        => 'Dashboard',
                'slug'        => 'dashboard',
                'http_method' => 'GET',
                'http_path'   => '/',
            ],
            [
                'name'        => 'Login',
                'slug'        => 'auth.login',
                'http_method' => '',
                'http_path'   => "/auth/login\r\n/auth/logout",
            ],
            [
                'name'        => 'User setting',
                'slug'        => 'auth.setting',
                'http_method' => 'GET,PUT',
                'http_path'   => '/auth/setting',
            ],
            [
                'name'        => 'Auth management',
                'slug'        => 'auth.management',
                'http_method' => '',
                'http_path'   => "/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs",
            ],
            [
                'name'        => 'CRUD Management',
                'slug'        => 'crud',
                'http_method' => '',
                'http_path'   => "/auth/crud",
            ],
            [
                'name'        => 'Database Management',
                'slug'        => 'auth.db',
                'http_method' => '',
                'http_path'   => "/adminer",
            ],
        ]);

        Role::first()->permissions()->save(Permission::first());

        // add default menus.
        Menu::truncate();
        Menu::insert([
            [
                // 
                'parent_id' => 0,
                'order'     => 1,
                'title'     => 'CRUD',
                'icon'      => 'fa-puzzle-piece',
                'uri'       => 'crud',
                'permission'=> 'crud'
            ],
            [
                'parent_id' => 0,
                'order'     => 2,
                'title'     => 'Dashboard',
                'icon'      => 'fa-bar-chart',
                'uri'       => '/',
                'permission'=> null
            ],
            [
                'parent_id' => 0,
                'order'     => 3,
                'title'     => 'Admin',
                'icon'      => 'fa-tasks',
                'uri'       => '',
                'permission'=> '*'
            ],
            [
                'parent_id' => 3,
                'order'     => 4,
                'title'     => 'Users',
                'icon'      => 'fa-users',
                'uri'       => 'auth/users',
                'permission'=> null
            ],
            [
                'parent_id' => 3,
                'order'     => 5,
                'title'     => 'Roles',
                'icon'      => 'fa-user',
                'uri'       => 'auth/roles',
                'permission'=> null
            ],
            [
                'parent_id' => 3,
                'order'     => 6,
                'title'     => 'Permission',
                'icon'      => 'fa-ban',
                'uri'       => 'auth/permissions',
                'permission'=> null
            ],
            [
                'parent_id' => 3,
                'order'     => 7,
                'title'     => 'Menu',
                'icon'      => 'fa-bars',
                'uri'       => 'auth/menu',
                'permission'=> null
            ],
            [
                'parent_id' => 3,
                'order'     => 8,
                'title'     => 'Operation log',
                'icon'      => 'fa-history',
                'uri'       => 'auth/logs',
                'permission'=> null
            ],
            [
                'parent_id' => 3,
                'order'     => 9,
                'title'     => 'Adminer',
                'icon'      => 'fa-database',
                'uri'       => 'http://'.env('APP_URL').'/admin/adminer',
                'permission'=> 'auth.db'
            ],
        ]);

        // add role to menu.
        Menu::find(2)->roles()->save(Role::first());
    }
}
