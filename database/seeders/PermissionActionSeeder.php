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
                'name' => 'create',
                'icon' => '<i class="bi bi-plus-lg text-muted me-2"></i>'
            ],
            [
                'name' => 'view',
                'icon' => '<i class="bi bi-eye text-muted me-2"></i>'
            ],
            [
                'name' => 'edit',
                'icon' => '<i class="bi bi-pencil text-muted me-2"></i>'
            ],
            [
                'name' => 'delete',
                'icon' => '<i class="bi bi-trash text-muted me-2"></i>'
            ],
            [
                'name' => 'approve',
                'icon' => '<i class="bi bi-hand-thumbs-up text-muted me-2"></i>'
            ],
            [
                'name' => 'disapprove',
                'icon' => '<i class="bi bi-hand-thumbs-down text-muted me-2"></i>'
            ],
            [
                'name' => 'forward',
                'icon' => '<i class="bi bi-reply text-muted me-2"></i>'
            ],
            [
                'name' => 'close',
                'icon' => '<i class="bi bi-check-lg text-muted me-2"></i>'
            ],
        ];

        foreach ($permissionActions as $action) {
            PermissionAction::firstOrCreate([
                'name' => $action['name'],
                'icon' => $action['icon']
            ]);
        }

    }
}
