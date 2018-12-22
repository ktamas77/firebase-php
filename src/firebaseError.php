<?php
namespace Firebase;

/**
 * Class Error
 *
 * @package Firebase
 */
class Error
{
    public $error;
    public $message;

    /**
     * @param $error
     * @param $message
     */
    public function __construct($error, $message)
    {
        $this->error = $error;
        $this->message = $message;
    }
}
