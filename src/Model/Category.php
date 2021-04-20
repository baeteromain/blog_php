<?php

namespace App\Model;

class Category
{
    private $id;

    private $name;

    private $slug;

    /**
     * Get the value of id.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the value of slug.
     */
    public function getSlug()
    {
        return $this->slug;
    }

}
