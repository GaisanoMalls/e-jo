<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            BranchSeeder::class,
            StatusesSeeder::class,
            DepartmentSeeder::class,
            PriorityLevelsSeeder::class,
            ServiceDepartmentSeeder::class,
            TeamSeeder::class,
            TagSeeder::class,
            RolesSeeder::class,
            UserSeeder::class,
            ProfileSeeder::class,
            SuffixesSeeder::class,
            LevelSeeder::class,
            ServiceLevelAgreementSeeder::class,
            PermissionSeeder::class,
            PermissionViaRolesSeeder::class,
        ]);
    }
}