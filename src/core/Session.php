<?php

namespace App\core;

class Session
{
    public function set($name, $value): Session
    {
        $_SESSION[$name] = $value;

        return $this;
    }

    public function get($name)
    {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }

        return null;
    }

    public function show($name)
    {
        if (isset($_SESSION[$name])) {
            $key = $this->get($name);
            $this->remove($name);

            return $key;
        }

        return null;
    }

    public function remove($name)
    {
        unset($_SESSION[$name]);
    }

    public function update($name, $param, $value): Session
    {
        unset($_SESSION[$name][$param]);
        $_SESSION[$name][$param] = $value;

        return $this;
    }

    public function start()
    {
        session_start();
    }

    public function stop()
    {
        session_destroy();
    }
}
