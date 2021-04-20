<?php

namespace App\Model;

class Post
{
    private $id;

    private $title;

    private $slug;

    private $filename;

    private $chapo;

    private $content;

    private $created_at;

    private $update_at;

    private $user_id;

    /**
     * Get the value of id.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of tile.
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get the value of slug.
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Get the value of filename.
     */
    public function getFilename()
    {
        return $this->filename;
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
     * Get the value of update_at.
     */
    public function getUpdate_at()
    {
        return $this->update_at;
    }

    /**
     * Get the value of user_id.
     */
    public function getUser_id()
    {
        return $this->user_id;
    }

    /**
     * Get the value of chapo.
     */
    public function getChapo()
    {
        return $this->chapo;
    }

}
