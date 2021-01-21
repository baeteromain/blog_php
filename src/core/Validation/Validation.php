<?php

namespace App\core\Validation;

class Validation
{
    public function validate($data, $name)
    {
        if ('User' === $name) {
            $userValidation = new UserValidation();

            return $userValidation->check($data);
        }

        // if ('Article' === $name) {
        //     $articleValidation = new ArticleValidation();

        //     return $articleValidation->check($data);
        // }
        // if ('Comment' === $name) {
        //     $commentValidation = new CommentValidation();

        //     return $commentValidation->check($data);
        // }

        return null;
    }
}
