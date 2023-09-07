<?php

namespace Database\Seeders;

use App\Http\Traits\Utils;
use App\Models\Profile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    use Utils;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Profile::firstOrCreate([
            'first_name' => 'Sam',
            'middle_name' => 'C',
            'last_name' => 'Sabellano',
            'suffix' => 'Jr',
            'mobile_number' => '09123456789',
            'department_phone_number' => '702-484-6811',
            'user_id' => 1,
            'slug' => self::slugify('Sam C. Sabellano Jr.')
        ]);
    }
}