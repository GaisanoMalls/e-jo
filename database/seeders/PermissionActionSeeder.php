<?php

namespace Database\Seeders;

use App\Models\PermissionAction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissionActions = [
            [
                'name' => 'Create',
                'icon' => '<i class="bi bi-plus-lg text-muted me-2"></i>',
            ],
            [
                'name' => 'View',
                'icon' => '<i class="bi bi-eye text-muted me-2"></i>',
            ],
            [
                'name' => 'Edit',
                'icon' => '<i class="bi bi-pencil text-muted me-2"></i>',
            ],
            [
                'name' => 'Approve',
                'icon' => '<i class="bi bi-hand-thumbs-up text-muted me-2"></i>',
            ],
            [
                'name' => 'Disapprove',
                'icon' => '<i class="bi bi-hand-thumbs-down text-muted me-2"></i>',
            ],
            [
                'name' => 'Forward',
                'icon' => '<i class="bi bi-reply text-muted me-2"></i>',
            ],
            [
                'name' => 'Close',
                'icon' => '<i class="bi bi-check-lg text-muted me-2"></i>',
            ],
        ];

        foreach ($permissionActions as $action) {
            PermissionAction::firstOrCreate([
                'name' => $action['name'],
                'icon' => $action['icon'],
            ]);
        }

    }
}
