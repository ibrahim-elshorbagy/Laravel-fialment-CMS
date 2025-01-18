<?php

namespace Database\Seeders;

use App\Models\Article;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'a',
            'email' => 'a@a.a',
            'password' => Hash::make('a'),
        ]);

        User::factory()->create([
            'name' => 'x',
            'email' => 'x@x.x',
            'password' => Hash::make('x'),
        ]);
         $faker = Faker::create();

        for ($i = 1; $i <= 6; $i++) {

            Article::create([
                'title' => $faker->sentence,
                'slug' => $faker->slug,
                'user_id' => 1,
                'content' => $faker->paragraphs(3, true),
                'brief' => $faker->paragraph(1),
                'media_id' => null,
                'is_published' => $i % 2 == 0 ? true : false
            ]);
        }

    }
}
