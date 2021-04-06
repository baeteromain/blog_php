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
     * Set the value of id.
     *
     * @param mixed $id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of content.
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the value of content.
     *
     * @param mixed $content
     *
     * @return self
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the value of created_at.
     */
    public function getCreated_at()
    {
        return $this->created_at;
    }

    /**
     * Set the value of created_at.
     *
     * @param mixed $created_at
     *
     * @return self
     */
    public function setCreated_at($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Get the value of publish.
     */
    public function getPublish()
    {
        return $this->publish;
    }

    /**
     * Set the value of publish.
     *
     * @param mixed $publish
     *
     * @return self
     */
    public function setPublish($publish)
    {
        $this->publish = $publish;

        return $this;
    }

    /**
     * Get the value of comment_id.
     */
    public function getComment_id()
    {
        return $this->comment_id;
    }

    /**
     * Set the value of comment_id.
     *
     * @param mixed $comment_id
     *
     * @return self
     */
    public function setComment_id($comment_id)
    {
        $this->comment_id = $comment_id;

        return $this;
    }

    /**
     * Get the value of post_id.
     */
    public function getPost_id()
    {
        return $this->post_id;
    }

    /**
     * Set the value of post_id.
     *
     * @param mixed $post_id
     *
     * @return self
     */
    public function setPost_id($post_id)
    {
        $this->post_id = $post_id;

        return $this;
    }

    /**
     * Get the value of user_id.
     */
    public function getUser_id()
    {
        return $this->user_id;
    }

    /**
     * Set the value of user_id.
     *
     * @param mixed $user_id
     *
     * @return self
     */
    public function setUser_id($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }
}
