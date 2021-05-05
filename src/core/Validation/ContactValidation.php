<?php

namespace App\core\Validation;

class ContactValidation
{
    private $errors = [];

    /**
     * @var Constraint
     */
    private $constraint;

    public function __construct()
    {
        $this->constraint = new Constraint();
    }

    public function check($get): array
    {
        foreach ($get as $key => $value) {
            $this->checkField($key, $value);
        }

        return $this->errors;
    }

    private function checkField($name, $value)
    {
        if ('firstname' === $name) {
            $error = $this->checkFistName($name, $value);

            return $this->addError($name, $error);
        }
        if ('lastname' === $name) {
            $error = $this->checkLastName($name, $value);

            return $this->addError($name, $error);
        }
        if ('email' === $name) {
            $error = $this->checkEmail($name, $value);

            return $this->addError($name, $error);
        }
        if ('content' === $name) {
            $error = $this->checkContent($name, $value);

            return $this->addError($name, $error);
        }

        return $this->addError('form_failed_contact', 'Une erreur est survenue, merci de resaisir vos informations');
    }

    private function addError($name, $error)
    {
        if ($error) {
            $this->errors += [
                $name => $error,
            ];
        }
    }

    private function checkFistName($name, $value)
    {
        if ($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('prénom', $value);
        }

        if ($this->constraint->maxLength($name, $value, 255)) {
            return $this->constraint->maxLength('prénom', $value, 255);
        }
    }

    private function checkLastName($name, $value)
    {
        if ($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('nom', $value);
        }

        if ($this->constraint->maxLength($name, $value, 255)) {
            return $this->constraint->maxLength('nom', $value, 255);
        }
    }

    private function checkEmail($name, $value)
    {
        if ($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('email', $value);
        }
        if ($this->constraint->isEmail($name, $value)) {
            return $this->constraint->isEmail('email', $value);
        }
    }

    private function checkContent($name, $value)
    {
        if ($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('message', $value);
        }
    }
}
