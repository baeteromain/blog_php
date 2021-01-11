<?php 
namespace App\core\Validation;

use Exception;
use App\core\Validation\Constraint;

class Validator extends Constraint{

    private $data;
    private $errors = [];
   
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function validate($name)
    {
        if($name === 'register'){
            $this->validateUsername('username');
            $this->validateEmail('email');
            $this->validatePassword('password');
            $this->validateConfirmPassword('confirm_password');
            return $this->errors;
        } else {
            throw new Exception('Impossible de faire la vérification');
        }  
    }

    private function validateUsername($post_key)
    {
        $val = trim($this->data[$post_key]);

        $errorBlank = $this->notBlank('pseudo', $val);
        $errorMinLength = $this->minLength('pseudo', $val, 3);
        $errorMaxLength = $this->maxLength('pseudo', $val, 45);

        if($errorBlank){
            $this->addError($post_key, $errorBlank);
        }elseif($errorMinLength) {
            $this->addError($post_key, $errorMinLength);
        }elseif($errorMaxLength){
            $this->addError($post_key, $errorMaxLength);
        }
    }

    private function validateEmail($post_key)
    {
        $val = trim($this->data[$post_key]);

        $errorBlank = $this->notBlank('email', $val);
        $errorIsEmail = $this->isEmail('email', $val);

        if($errorBlank){
            $this->addError($post_key, $errorBlank);
        }elseif($errorIsEmail) {
            $this->addError($post_key, $errorIsEmail);
        }
    }

    private function validatePassword($post_key)
    {
        $val = $this->data[$post_key];

        $errorBlank = $this->notBlank('password', $val);
        $errorMinLength = $this->minLength('password', $val, 4);
        $errorMaxLength = $this->maxLength('password', $val, 60);

        if($errorBlank){
            $this->addError($post_key, $errorBlank);
        }elseif($errorMinLength) {
            $this->addError($post_key, $errorMinLength);
        }elseif($errorMaxLength){
            $this->addError($post_key, $errorMaxLength);
        }

    }

    private function validateConfirmPassword($post_key)
    {
        $val = $this->data[$post_key];

        $errorBlank = $this->notBlank('Confirmation password', $val);
        $errorMatch = $this->matchPassword('Confirmation password', $val, $this->data['password']);

        if($errorBlank){
            $this->addError($post_key, $errorBlank);
        }elseif($errorMatch) {
            $this->addError($post_key, $errorMatch);
        }
    }

    private function addError($key, $val)
    {
        $this->errors[$key] = $val;
    }

}