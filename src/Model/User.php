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
     * Get the value of username.
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of username.
     *
     * @param mixed $username
     *
     * @return self
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of email.
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email.
     *
     * @param mixed $email
     *
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password.
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password.
     *
     * @param mixed $password
     *
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of role_id.
     */
    public function getRole_id()
    {
        return $this->role_id;
    }

    /**
     * Set the value of role_id.
     *
     * @param mixed $role_id
     *
     * @return self
     */
    public function setRole_id($role_id)
    {
        $this->role_id = $role_id;

        return $this;
    }

    /**
     * Get the value of token.
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set the value of token.
     *
     * @param mixed $token
     *
     * @return self
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get the value of valid.
     */
    public function getValid()
    {
        return $this->valid;
    }

    /**
     * Set the value of valid.
     *
     * @param mixed $valid
     *
     * @return self
     */
    public function setValid($valid)
    {
        $this->valid = $valid;

        return $this;
    }
}
