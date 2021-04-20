<?php

namespace App\Model;

class User
{
    private $id;

    private $username;

    private $email;

    private $password;

    private $role_id;

    private $token;

    private $valid;

    /**
     * Get the value of id.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of username.
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get the value of email.
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get the value of password.
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get the value of role_id.
     */
    public function getRole_id()
    {
        return $this->role_id;
    }

    /**
     * Get the value of token.
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Get the value of valid.
     */
    public function getValid()
    {
        return $this->valid;
    }

}
