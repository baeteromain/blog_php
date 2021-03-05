<?php

namespace App\Manager;

use App\core\Database;
use App\Model\Category;
use App\Model\Post;
use DateTime;
use DateTimeZone;
use PDO;

class PostManager extends Database
{
    public function getPosts()
    {
        $query = $this->createQuery(
            'SELECT post.id, post.title, post.slug, post.filename, post.content, post.created_at, post.update_at, user.username as autor 
            FROM post
            INNER JOIN user ON post.user_id = user.id 
            ORDER BY post.created_at DESC
            '
        );
        $query->setFetchMode(PDO::FETCH_CLASS, Post::class);

        return $query->fetchAll();
    }

    public function addCategoryToPost($categoryId, $postTitle)
    {
        return $this->createQuery(
            'INSERT INTO post_category 
            (category_id , post_id)                
             SELECT  :categoryId, id    
            FROM post WHERE title = :postTitle',
            ['postTitle' => $postTitle,
                'categoryId' => $categoryId,
            ]
        );
    }

    public function getPostById($id)
    {
        $query = $this->createQuery(
            'SELECT post.id, post.title, post.chapo, post.slug, post.filename, post.content, post.created_at, post.update_at, post.user_id, post.update_at, category_id
            FROM post LEFT OUTER JOIN post_category ON post.id = post_category.post_id
            WHERE id = :id',
            [
                'id' => $id,
            ]
        );

        $query->setFetchMode(PDO::FETCH_CLASS, Post::class);
        $post = $query->fetch();
        if ($post) {
            return $post;
        }

        return false;
    }

    public function getCategoryByPost($id)
    {
        $query = $this->createQuery(
            'SELECT c.id, c.slug, c.name 
            FROM post_category pc 
            JOIN category c ON pc.category_id = c.id
            WHERE pc.post_id = :id',
            [
                'id' => $id,
            ]
        );

        $query->setFetchMode(PDO::FETCH_CLASS, Category::class);

        return $query->fetchAll();
    }

    public function createPost($title, $slug, $filename, $chapo, $content, $user_id)
    {
        $datePost = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $datePost = $datePost->format('Y-m-d H:i:s');

        return $this->createQuery(
            'INSERT INTO post (title, slug, filename, chapo, content, created_at, user_id) 
            VALUES (:title, :slug,  :filename, :chapo, :content, :created_at, :user_id)',
            [
                'title' => $title,
                'slug' => $slug,
                'chapo' => $chapo,
                'filename' => $filename,
                'content' => $content,
                'user_id' => $user_id,
                'created_at' => $datePost,
            ]
        );
    }

    public function updatePost($id, $title, $slug, $filename, $chapo, $content, $created_at, $user_id)
    {
        $dateUpdatePost = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $dateUpdatePost = $dateUpdatePost->format('Y-m-d H:i:s');

        return $this->createQuery(
            '
        UPDATE post SET title = :title, slug = :slug, filename = :filename, chapo = :chapo, content = :content, created_at = :created_at, update_at = :update_at, user_id = :user_id
        WHERE id = :id',
            [
                'id' => $id,
                'title' => $title,
                'slug' => $slug,
                'filename' => $filename,
                'chapo' => $chapo,
                'created_at' => $created_at,
                'content' => $content,
                'update_at' => $dateUpdatePost,
                'user_id' => $user_id,
            ]
        );
    }

    public function deleteCategoryOfPost($categoryId, $postId)
    {
        return $this->createQuery(
            'DELETE FROM post_category WHERE post_id = :post_id AND category_id = :category_id',
            [
                'category_id' => $categoryId,
                'post_id' => $postId,
            ]
        );
    }

    public function checkTitleUnique($title)
    {
        $result = $this->createQuery(
            '
            SELECT COUNT(title)
            FROM post
            WHERE title = :title
            ',
            [
                'title' => $title,
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
            FROM post
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
