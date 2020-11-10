<?php

namespace RefactorGame\Helpers;

use RefactorGame\Category;

class Categories
{
    public static function createCategories():array
    {
        return [
            Category::POP => new Category(Category::POP),
            Category::SCIENCE => new Category(Category::SCIENCE),
            Category::SPORTS => new Category(Category::SPORTS),
            Category::ROCK => new Category(Category::ROCK),
        ];
    }
}
