<?php

namespace App\Manager;

use App\core\Database;
use DateTime;
use DateTimeZone;

class CommentManager extends Database
{
    public function addComment($content, $comment_id, $post_id, $user_id)
    {
        $datePost = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $datePost = $datePost->format('Y-m-d H:i:s');

        return $this->createQuery(
            'INSERT INTO comment (content, created_at, comment_id, post_id, user_id) 
            VALUES (:content, :created_at, :comment_id, :post_id, :user_id)',
            [
                'content' => $content,
                'created_at' => $datePost,
                'comment_id' => $comment_id,
                'post_id' => $post_id,
                'user_id' => $user_id,
            ]
        );
    }
}
