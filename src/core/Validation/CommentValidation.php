<?php

namespace App\core\Validation;

use App\Manager\CommentManager;

class CommentValidation
{
    private $errors = [];

    /**
     * @var Constraint
     */
    private $constraint;
    /**
     * @var CommentManager
     */
    private $commentManager;


    public function __construct()
    {
        $this->constraint = new Constraint();
        $this->commentManager = new CommentManager();
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
        if ('content' === $name) {
            $error = $this->checkContent($name, $value);
            $this->addError($name, $error);
        } elseif ('reply' === $name) {
            $error = $this->checkContentReply($name, $value);
            $this->addError($name, $error);
        } elseif ('post_id' === $name) {
            return null;
        } elseif ('comment_id' === $name) {
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

    private function checkContentReply($name, $value)
    {
        if ($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('réponse', $value);
        }
        if ($this->constraint->maxLength($name, $value, 255)) {
            return $this->constraint->maxLength('réponse', $value, 255);
        }
    }
}
