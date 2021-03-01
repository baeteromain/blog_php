<?php

namespace App\Manager;

use App\core\Database;
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

    public function addCategoryToPost($category, $post)
    {
        return $this->createQuery(
            'INSERT INTO post_category 
            (category_id , post_id)                
             SELECT  :category, id    
            FROM post WHERE title = :post',
            ['post' => $post,
                'category' => $category,
            ]
        );
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
