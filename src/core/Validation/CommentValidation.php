<?php

namespace App\core\Validation;

use App\Manager\CommentManager;

class CommentValidation
{
    private $errors = [];
    private $constraint;
    private $commentManager;

    public function __construct()
    {
        $this->constraint = new Constraint();
        $this->commentManager = new CommentManager();
    }

    public function check($get)
    {
        foreach ($get as $key => $value) {
            $this->checkField($key, $value);
        }

        return $this->errors;
    }

    private function checkField($name, $value)
    {
        if ('content' === $name) {
            $error = $this->checkContent($name, $value);
            $this->addError($name, $error);
        } elseif ('post_id' === $name) {
            return null;
        } else {
            $this->addError('form_failed_comment', 'Une erreur est survenue, merci de resaisir vos informations');
        }
    }

    private function addError($name, $error)
    {
        if ($error) {
            $this->errors += [
                $name => $error,
            ];
        }
    }

    private function checkContent($name, $value)
    {
        if ($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('commentaire', $value);
        }
        if ($this->constraint->maxLength($name, $value, 255)) {
            return $this->constraint->maxLength('commentaire', $value, 255);
        }
    }
}
