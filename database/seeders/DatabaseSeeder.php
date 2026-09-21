<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'admin', 'display_name' => 'Admin', 'description' => 'Admin'],
            ['name' => 'user', 'display_name' => 'User', 'description' => 'User'],
            ['name' => 'vendor', 'display_name' => 'Vendor', 'description' => 'Vendor'],
            ['name' => 'hrRep', 'display_name' => 'HRRep', 'description' => 'HRRep'],
            ['name' => 'mediaRep', 'display_name' => 'MediaRep', 'description' => 'MediaRep'],
            ['name' => 'orgRep', 'display_name' => 'OrgRep', 'description' => 'OrgRep'],
            ['name' => 'media', 'display_name' => 'Media', 'description' => 'Media'],
            ['name' => 'depoRep', 'display_name' => 'DepoRep', 'description' => 'DepoRep'],
            ['name' => 'depo', 'display_name' => 'Depo', 'description' => 'Depo'],
            ['name' => 'bxssUser', 'display_name' => 'BxssUser', 'description' => 'BxssUser'],
            ['name' => 'attandeeUser', 'display_name' => 'AttandeeUser', 'description' => 'AttandeeUser'],
            ['name' => 'web_user', 'display_name' => 'WebUser', 'description' => 'Web User'],
            ['name' => 'printer', 'display_name' => 'Printer', 'description' => 'Printer'],
            ['name' => 'sender', 'display_name' => 'Sender', 'description' => 'Sender'],
            ['name' => 'authority', 'display_name' => 'Authority', 'description' => 'Authority'],
            ['name' => 'temporaryPass', 'display_name' => 'TemporaryPass', 'description' => 'TemporaryPass'],
            ['name' => 'dataScan', 'display_name' => 'DataScan', 'description' => 'DataScan'],
            ['name' => 'ncc', 'display_name' => 'NCC', 'description' => 'NCC'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                [
                    'display_name' => $role['display_name'],
                    'description' => $role['description'],
                ]
            );
        }

        $admin = User::firstOrNew(['email' => 'abdul.moeid@badarexpo.com']);

        $admin->fill([
            'name' => 'admin',
            'password' => Hash::make('Tang9211*'),
            'activated' => 1,
            'status' => 1,
        ]);

        if (! $admin->exists) {
            $admin->uid = (string) Str::uuid();
        }

        $admin->save();

        $adminRole = Role::where('name', 'admin')->first();

        DB::table('role_user')->updateOrInsert([
            'role_id' => $adminRole->id,
            'user_id' => $admin->id,
            'user_type' => User::class,
        ]);
    }
}
