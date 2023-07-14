<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = ['Account', 'System Issue', 'Complaint', 'Feedback', 'Support', 'Request'];

        foreach ($tags as $tag) {
            Tag::firstOrCreate([
                'name' => $tag,
                'slug' => \Str::slug($tag)
            ]);
        }
    }
}