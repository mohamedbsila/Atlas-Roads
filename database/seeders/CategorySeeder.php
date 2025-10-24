<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'category_name' => 'Fiction',
                'description' => 'Novels, short stories, and other fictional works'
            ],
            [
                'category_name' => 'Non-Fiction',
                'description' => 'Factual books including biographies, history, and self-help'
            ],
            [
                'category_name' => 'Science & Technology',
                'description' => 'Books about scientific discoveries, technology, and innovation'
            ],
            [
                'category_name' => 'Business & Economics',
                'description' => 'Books about business, finance, and economic theory'
            ],
            [
                'category_name' => 'History',
                'description' => 'Historical accounts, biographies, and cultural studies'
            ],
            [
                'category_name' => 'Philosophy',
                'description' => 'Philosophical texts and discussions of ideas'
            ],
            [
                'category_name' => 'Arts & Literature',
                'description' => 'Books about art, literature criticism, and creative writing'
            ],
            [
                'category_name' => 'Education',
                'description' => 'Educational materials, textbooks, and learning resources'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
