<?php

namespace App\Manager;

use App\core\Database;
use App\Model\Category;
use PDO;

class CategoryManager extends Database
{
    public function getCategoryById($id)
    {
        $query = $this->createQuery(
            '
            SELECT * FROM category
            WHERE id = :id
            ',
            [
                'id' => $id,
            ]
        );

        $query->setFetchMode(PDO::FETCH_CLASS, Category::class);
        $category = $query->fetch();
        if ($category) {
            return $category;
        }

        return false;
    }

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

    public function updateCategory($id, $name, $slug)
    {
        return $this->createQuery(
            '
        UPDATE category SET id = :id, name = :name, slug = :slug
        WHERE id = :id',
            [
                'id' => $id,
                'name' => $name,
                'slug' => $slug,
            ]
        );
    }

    public function deleteCategory($id)
    {
        return $this->createQuery(
            '
        DELETE FROM category 
        WHERE id = :id',
            [
                'id' => $id,
            ]
        );
    }

    public function checkNameUnique($name)
    {
        $result = $this->createQuery(
            '
            SELECT COUNT(name)
            FROM category
            WHERE name = :name
            ',
            [
                'name' => $name,
            ]
        );

        $exist = $result->fetchColumn();
        if ($exist) {
            return true;
        }

        return false;
    }

    public function checkSlugUnique($slug)
    {
        $result = $this->createQuery(
            '
            SELECT COUNT(slug)
            FROM category
            WHERE slug = :slug
            ',
            [
                'slug' => $slug,
            ]
        );

        $exist = $result->fetchColumn();
        if ($exist) {
            return true;
        }

        return false;
    }
}
