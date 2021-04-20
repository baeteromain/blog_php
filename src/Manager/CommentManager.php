<?php

namespace App\Manager;

use App\core\Database;
use App\Model\Comment;
use DateTime;
use DateTimeZone;
use PDO;

class CommentManager extends Database
{
    public function totalComments($filtre)
    {
        $result = $this->createQuery(
            'SELECT COUNT(id) FROM comment WHERE comment_id IS NULL AND publish = :publish',
            [
                'publish' => $filtre,
            ]
        );

        return $result->fetchColumn();
    }

    public function totalReplyComments($filtre)
    {
        $result = $this->createQuery(
            'SELECT COUNT(id) FROM comment WHERE comment_id IS NOT NULL AND publish = :publish',
            [
                'publish' => $filtre,
            ]
        );

        return $result->fetchColumn();
    }

    public function getComments()
    {
        $query = $this->createQuery(
            '
            SELECT comment.*, post.title as title_post, user.username FROM comment 
            INNER JOIN user ON user.id = comment.user_id 
            INNER JOIN post ON post.id = comment.post_id 
            ORDER BY created_at DESC
            '
        );
        $query->setFetchMode(PDO::FETCH_CLASS, Comment::class);

        return $query->fetchAll();
    }

    public function getCommentsFilter($filtre, $limit = null, $start = null)
    {
        $sql = 'SELECT comment.*, post.title as title_post, user.username FROM comment 
            INNER JOIN user ON user.id = comment.user_id 
            INNER JOIN post ON post.id = comment.post_id
            WHERE publish = :publish ORDER BY created_at DESC';

        if ($limit) {
            $sql .= ' LIMIT '.$limit.' OFFSET '.$start;
        }
        $query = $this->createQuery(
            $sql,
            [
                'publish' => $filtre,
            ]
        );

        $query->setFetchMode(PDO::FETCH_CLASS, Comment::class);

        return $query->fetchAll();
    }

    public function getCommentsByPost($post_id)
    {
        $query = $this->createQuery(
            '
            SELECT comment.*, post.title as title_post, user.username FROM comment 
            INNER JOIN user ON user.id = comment.user_id 
            INNER JOIN post ON post.id = comment.post_id
            WHERE post.id = :id AND comment.comment_id IS NULL
            ORDER BY created_at DESC
            ',
            [
                'id' => $post_id,
            ]
        );
        $query->setFetchMode(PDO::FETCH_CLASS, Comment::class);

        return $query->fetchAll();
    }

    public function getReplyByComment($comment_id)
    {
        $query = $this->createQuery(
            '
            SELECT comment.*, user.username FROM comment 
            INNER JOIN user ON user.id = comment.user_id 
            WHERE comment_id = :id 
            ORDER BY created_at ASC
            ',
            [
                'id' => $comment_id,
            ]
        );
        $query->setFetchMode(PDO::FETCH_CLASS, Comment::class);

        return $query->fetchAll();
    }

    public function addComment($content, $comment_id, $post_id, $user_id)
    {
        $datePost = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $datePost = $datePost->format('Y-m-d H:i:s');

        return $this->createQuery(
            '
            INSERT INTO comment (content, created_at, comment_id, post_id, user_id) 
            VALUES (:content, :created_at, :comment_id, :post_id, :user_id)
            ',
            [
                'content' => $content,
                'created_at' => $datePost,
                'comment_id' => $comment_id,
                'post_id' => $post_id,
                'user_id' => $user_id,
            ]
        );
    }

    public function validateComment($id)
    {
        return $this->createQuery(
            'UPDATE comment SET publish = 1 WHERE id = :id',
            [
                'id' => $id,
            ]
        );
    }

    public function deleteComment($id)
    {
        return $this->createQuery(
            ' DELETE FROM comment WHERE id = :id',
            [
                'id' => $id,
            ]
        );
    }
}
