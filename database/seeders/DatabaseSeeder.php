<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
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

        $articles = [
            [
                'id' => 1,
                'user_id' => 1,
                'title' => 'Introduction to Laravel',
                'slug' => 'introduction-to-laravel',
                'published_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'is_published' => true,
                'content' => 'In this article, we will explore the basics of Laravel development and its core features. We will delve into topics such as routing, controllers, models, views, migrations, and more. By the end of this tutorial, you will have a solid understanding of how to build web applications using the Laravel framework.',
                'brief' => 'Learn the basics of Laravel development and its core features.',
                'media' => [
                    'path' => 'https://placehold.co/640x480?text=Laravel+Introduction',
                    'alt' => 'Laravel Introduction',
                ],
            ],
            [
                'id' => 2,
                'user_id' => 1,
                'title' => 'Getting Started with Laravel Tutorials',
                'slug' => 'getting-started-with-laravel-tutorials',
                'published_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'is_published' => true,

                'content' => 'This article will guide you through the initial steps of learning Laravel through tutorials. We will cover setting up your development environment, creating your first Laravel project, understanding the Laravel directory structure, and basic concepts such as routing and views. By following along with this tutorial, you will be well-equipped to begin your journey into Laravel development.',

                'brief' => 'Learn the basics of Laravel development through tutorials.',
                'media' => [
                    'path' => 'https://placehold.co/640x480?text=Laravel+Tutorials',
                    'alt' => 'Laravel Tutorials',
                ],
            ],
            [
                'id' => 3,
                'user_id' => 1,
                'title' => 'Exploring Laravel Packages',
                'slug' => 'exploring-laravel-packages',
                'published_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'is_published' => true,

                'content' => 'This article will delve into various Laravel packages available in the Laravel ecosystem. We will discuss popular packages for authentication, authorization, caching, validation, and more. By the end of this exploration, you will have a good understanding of how to leverage Laravel packages to enhance your applications.',

                'brief' => 'Discover the power of Laravel packages.',
                'media' => [
                    'path' => 'https://placehold.co/640x480?text=Laravel+Packages',
                    'alt' => 'Laravel Packages',
                ],
            ],
            [
                'id' => 4,
                'user_id' => 1,
                'title' => 'Latest Laravel News',
                'slug' => 'latest-laravel-news',
                'published_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'is_published' => true,

                'content' => 'Stay updated with the latest news and announcements from the Laravel community. In this article, we will cover recent releases, upcoming events, community highlights, and more. Whether you are a seasoned Laravel developer or just getting started, this article will keep you informed about the latest developments in the Laravel world.',
                'brief' => 'Stay up to date with the latest Laravel news.',
                'media' => [
                    'path' => 'https://placehold.co/640x480?text=Laravel+News',
                    'alt' => 'Laravel News',
                ],
            ],
            [
                'id' => 5,
                'user_id' => 1,
                'title' => 'Introduction to PHP',
                'slug' => 'introduction-to-php',
                'published_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'is_published' => true,

                'content' => 'PHP is a powerful server-side scripting language that is widely used for web development. In this article, we will provide an introduction to PHP, covering basic syntax, data types, control structures, functions, and more. Whether you are new to programming or experienced in other languages, this article will help you get started with PHP.',
                'brief' => 'Learn the basics of PHP programming.',
                'media' => [
                    'path' => 'https://placehold.co/640x480?text=PHP+Introduction',
                    'alt' => 'PHP Introduction',
                ],
            ],
            [
                'id' => 6,
                'user_id' => 1,
                'title' => 'Essential CSS Concepts',
                'slug' => 'essential-css-concepts',
                'published_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'is_published' => true,

                'content' => 'CSS (Cascading Style Sheets) is a fundamental technology used for styling web pages. In this article, we will explore essential CSS concepts such as selectors, properties, values, inheritance, and specificity. Whether you are a beginner or looking to refresh your CSS skills, this article will provide a solid foundation in CSS.',
                'brief' => 'Learn the essential CSS concepts.',
                'media' => [
                    'path' => 'https://placehold.co/640x480?text=Css+Development',
                    'alt' => 'Css Development',
                ],
            ],
        ];
        foreach ($articles as $articleData) {
            // Create the article
            $article = Article::create([
                'id' => $articleData['id'],
                'user_id' => $articleData['user_id'],
                'title' => $articleData['title'],
                'slug' => $articleData['slug'],
                'published_at' => $articleData['published_at'],
                'is_published' => $articleData['is_published'],
                'content' => $articleData['content'],
                'brief' => $articleData['brief'],
            ]);

            // Attach media
            $article->media()->create($articleData['media']);
        }



                $categories = [
            [
                'id'=>1,
                'title' => 'Laravel Development',
                'slug' => 'Laravel-Development',
                'bg_color' => 'red',
                'text_color' => 'blue',
            ],
            [
                'id'=>2,

                'title' => 'Laravel Tutorials',
                'slug' => 'Laravel-Tutorials',
                'bg_color' => 'red',
                'text_color' => 'pink',
            ],
            [
                'id'=>3,

                'title' => 'Laravel Packages',
                'slug' => 'Laravel-Packages',
                'bg_color' => 'yellow',
                'text_color' => 'green',
            ],
            [
                'id'=>4,

                'title' => 'Laravel News',
                'slug' => 'Laravel-News',
                'bg_color' => 'lime',
                'text_color' => 'indigo',
            ],
            [
                'id'=>5,

                'title' => 'PHP',
                'slug' => 'PHP',
                'bg_color' => 'pink',
                'text_color' => 'blue',
            ],
            [
                'id'=>6,

                'title' => 'Css',
                'slug' => 'Css',
                'bg_color' => 'indigo',
                'text_color' => 'lime',
            ],
        ];

        // Insert categories into the database
        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
