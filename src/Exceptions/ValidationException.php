<?php namespace GoCardless\Pro\Exceptions;

class ValidationException extends \Exception
{
    /**
     * @var array
     */
    protected $errors;

    public function __construct($message, $errors)
    {
        $this->message = $message;
        $this->errors = $errors;
    }

    public function getErrors()
    {
        return $this->errors();
    }

    public function errors()
    {
        return $this->errors;
    }
}