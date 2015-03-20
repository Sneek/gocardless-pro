<?php namespace GoCardless\Pro\Exceptions;

use Exception;

class InvalidStateException extends \Exception
{
    /**
     * @var array
     */
    protected $errors;

    public function __construct($message, $code, $errors = [])
    {
        $this->message = $message;
        $this->code = $code;
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