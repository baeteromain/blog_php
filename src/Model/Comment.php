<?php

namespace App\Model;

class Comment
{
    private $id;

    private $content;

    private $created_at;

    private $publish;

    private $comment_id;

    private $post_id;

    private $user_id;

    /**
     * Get the value of id.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of content.
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Get the value of created_at.
     */
    public function getCreated_at()
    {
        return $this->created_at;
    }

    /**
     * Get the value of publish.
     */
    public function getPublish()
    {
        return $this->publish;
    }

    /**
     * Get the value of comment_id.
     */
    public function getComment_id()
    {
        return $this->comment_id;
    }

    /**
     * Get the value of post_id.
     */
    public function getPost_id()
    {
        return $this->post_id;
    }

    /**
     * Get the value of user_id.
     */
    public function getUser_id()
    {
        return $this->user_id;
    }

}
