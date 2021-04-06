<?php

namespace App\core\Validation;

class Validation
{
    public function validate($data, $name)
    {
        if ('User' === $name) {
            $userValidation = new UserValidation($data);

            return $userValidation->check($data);
        }
        if ('Category' === $name) {
            $categoryValidation = new CategoryValidation($data);

            return $categoryValidation->check($data);
        }

        if ('Post' === $name) {
            $postValidation = new PostValidation($data);

            return $postValidation->check($data);
        }
        if ('Comment' === $name) {
            $commentValidation = new CommentValidation($data);

            return $commentValidation->check($data);
        }

        return null;
    }
}
