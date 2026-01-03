<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // Income
            ['name' => 'Salary', 'type' => 'income', 'color' => 'green'],
            ['name' => 'Freelance', 'type' => 'income', 'color' => 'blue'],
            ['name' => 'Gift', 'type' => 'income', 'color' => 'purple'],
            
            // Expense
            ['name' => 'Rent', 'type' => 'expense', 'color' => 'red'],
            ['name' => 'Groceries', 'type' => 'expense', 'color' => 'orange'],
            ['name' => 'Transport', 'type' => 'expense', 'color' => 'yellow'],
            ['name' => 'Entertainment', 'type' => 'expense', 'color' => 'pink'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}
