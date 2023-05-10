<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = $this->getInitialCategories();
        foreach ($categories as $category) {
            Category::updateOrCreate($category);
        }
    }

    private function getInitialCategories(): array
    {
        return [
            [
                "name" => "Alimentação",
            ],
            [
                "name" => "Saúde",
            ],
            [
                "name" => "Moradia",
            ],
            [
                "name" => "Transporte",
            ],
            [
                "name" => "Educação",
            ],
            [
                "name" => "Lazer",
            ],
            [
                "name" => "Imprevistos",
            ],
            [
                "name" => "Outras",
            ],
        ];
    }
}
