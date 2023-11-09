<?php

namespace Database\Seeders;

use App\Models\ServiceLevelAgreement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceLevelAgreementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $serviceLevelAgreements = [
            [
                'countdown_approach' => 24,
                'time_unit' => '1 Day'
            ],
            [
                'countdown_approach' => 48,
                'time_unit' => '2 Days'
            ],
            [
                'countdown_approach' => 72,
                'time_unit' => '3 Days'
            ],
            [
                'countdown_approach' => 96,
                'time_unit' => '4 Days'
            ],
        ];

        foreach ($serviceLevelAgreements as $sla) {
            ServiceLevelAgreement::firstOrCreate([
                'countdown_approach' => $sla['countdown_approach'],
                'time_unit' => $sla['time_unit'],
            ]);
        }
    }
}
