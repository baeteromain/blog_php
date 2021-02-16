<?php

namespace App\Manager;

use App\core\Database;
use App\Model\Category;
use PDO;

class CategoryManager extends Database
{
    public function getCategories()
    {
        $query = $this->createQuery(
            'SELECT * FROM category'
        );
        $query->setFetchMode(PDO::FETCH_CLASS, Category::class);

        return $query->fetchAll();
    }

    public function createCategory($name, $slug)
    {
        return $this->createQuery(
            '
        INSERT INTO category (name, slug ) 
        VALUES (:name, :slug)
        ',
            [
                'name' => $name,
                'slug' => $slug,
            ]
        );
    }
}
