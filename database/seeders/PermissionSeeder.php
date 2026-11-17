<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'Manage Dashboard', 'name_bn' => 'ড্যাশবোর্ড ম্যানেজ', 'slug' => 'dashboard.manage'],
            ['name' => 'Manage Players', 'name_bn' => 'প্লেয়ার ম্যানেজ', 'slug' => 'players.manage'],
            ['name' => 'Manage Teams', 'name_bn' => 'টিম ম্যানেজ', 'slug' => 'teams.manage'],
            ['name' => 'Manage Tournaments', 'name_bn' => 'টুর্নামেন্ট ম্যানেজ', 'slug' => 'tournaments.manage'],
            ['name' => 'Manage Matches', 'name_bn' => 'ম্যাচ ম্যানেজ', 'slug' => 'matches.manage'],
            ['name' => 'Manage Squads', 'name_bn' => 'স্কোয়াড ম্যানেজ', 'slug' => 'squads.manage'],
            ['name' => 'Manage Scoreboards', 'name_bn' => 'স্কোরবোর্ড ম্যানেজ', 'slug' => 'scoreboards.manage'],
            ['name' => 'Manage News', 'name_bn' => 'নিউজ ম্যানেজ', 'slug' => 'news.manage'],
            ['name' => 'Manage Gallery', 'name_bn' => 'গ্যালারি ম্যানেজ', 'slug' => 'gallery.manage'],
            ['name' => 'Manage Applications', 'name_bn' => 'আবেদন ম্যানেজ', 'slug' => 'applications.manage'],
            ['name' => 'Manage Sponsors', 'name_bn' => 'স্পনসর ম্যানেজ', 'slug' => 'sponsors.manage'],
            ['name' => 'Manage Locations', 'name_bn' => 'লোকেশন ম্যানেজ', 'slug' => 'locations.manage'],
            ['name' => 'Manage FAQs', 'name_bn' => 'এফএকিউ ম্যানেজ', 'slug' => 'faqs.manage'],
            ['name' => 'Manage Sliders', 'name_bn' => 'স্লাইডার ম্যানেজ', 'slug' => 'sliders.manage'],
            ['name' => 'Manage Role Icons', 'name_bn' => 'রোল আইকন ম্যানেজ', 'slug' => 'role_icons.manage'],
            ['name' => 'Manage Users', 'name_bn' => 'ইউজার ম্যানেজ', 'slug' => 'users.manage'],
            ['name' => 'Manage Roles', 'name_bn' => 'রোল ম্যানেজ', 'slug' => 'roles.manage'],
            ['name' => 'Manage Permissions', 'name_bn' => 'পারমিশন ম্যানেজ', 'slug' => 'permissions.manage'],
            ['name' => 'Manage Settings', 'name_bn' => 'সেটিংস ম্যানেজ', 'slug' => 'settings.manage'],
        ];

        foreach ($permissions as $index => $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                [
                    'name' => $permission['name'],
                    'name_bn' => $permission['name_bn'],
                    'sort_order' => $index + 1,
                ]
            );
        }

        $superAdmin = Role::where('name', 'Super Admin')->first();

        if ($superAdmin) {
            $superAdmin->permissions()->sync(Permission::pluck('id')->all());
        }
    }
}
