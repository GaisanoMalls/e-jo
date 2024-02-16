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
                'hours' => 1,
                'time_unit' => '1 Hour'
            ],
            [
                'hours' => 3,
                'time_unit' => '3 Hours'
            ],
            [
                'hours' => 7,
                'time_unit' => '7 Hours'
            ],
            [
                'hours' => 5,
                'time_unit' => '5 Hours'
            ],
            [
                'hours' => 24,
                'time_unit' => '1 Day'
            ],
            [
                'hours' => 48,
                'time_unit' => '2 Days'
            ],
            [
                'hours' => 72,
                'time_unit' => '3 Days'
            ],
            [
                'hours' => 96,
                'time_unit' => '4 Days'
            ],
        ];

        foreach ($serviceLevelAgreements as $sla) {
            ServiceLevelAgreement::firstOrCreate([
                'hours' => $sla['hours'],
                'time_unit' => $sla['time_unit'],
            ]);
        }
    }
}
