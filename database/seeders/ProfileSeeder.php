<?php

namespace Database\Seeders;

use App\Http\Traits\SlugGenerator;
use App\Models\Profile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    use SlugGenerator;
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
            'picture' => 'https://media.licdn.com/dms/image/C4D03AQGkq_tdLLKjkA/profile-displayphoto-shrink_800_800/0/1658888226767?e=2147483647&v=beta&t=cPKcsJJg8q58jarC72FpF9HwEgEiak6x2Ft4r2f02zI',
            'user_id' => 1,
            'slug' => self::slugify('Sam C. Sabellano Jr.')
        ]);
    }
}