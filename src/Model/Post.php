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
     * Get the value of tile.
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of tile.
     *
     * @param mixed $tile
     * @param mixed $title
     *
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of slug.
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set the value of slug.
     *
     * @param mixed $slug
     *
     * @return self
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get the value of filename.
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set the value of filename.
     *
     * @param mixed $filename
     *
     * @return self
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

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
     * Get the value of update_at.
     */
    public function getUpdate_at()
    {
        return $this->update_at;
    }

    /**
     * Set the value of update_at.
     *
     * @param mixed $update_at
     *
     * @return self
     */
    public function setUpdate_at($update_at)
    {
        $this->update_at = $update_at;

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

    /**
     * Get the value of chapo.
     */
    public function getChapo()
    {
        return $this->chapo;
    }

    /**
     * Set the value of chapo.
     *
     * @param mixed $chapo
     *
     * @return self
     */
    public function setChapo($chapo)
    {
        $this->chapo = $chapo;

        return $this;
    }
}
