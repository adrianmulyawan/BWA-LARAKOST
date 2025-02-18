<?php

namespace App\Repositories;

use App\Interfaces\CategoryRepositoryInterfaces;
use App\Models\Category;

// Kita implements interface CategoryRepositoryInterfaces
class CategoryRepository implements CategoryRepositoryInterfaces
{
    // Kita jalankan fungsi yang ada didalam interface yang kita implements
    public function getAllCategories()
    {
        return Category::all();
    }
}
