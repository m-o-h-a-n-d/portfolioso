<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $blogs = json_decode(file_get_contents(database_path('seeders/data/blogs.json')), true);

        foreach ($blogs as $blog) {
            Blog::create([
                'title' => $blog['title'],
                'slug' => $blog['slug'] ?? Str::slug($blog['title']),
                'excerpt' => $blog['excerpt'] ?? null,
                'category' => 'news',
                'content' => $blog['content'] ?? null,
                'cover' => $blog['cover'] ?? null,
                'date' => $blog['date'] ?? null,

            ]);
        }
    }
}
