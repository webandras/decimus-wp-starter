<?php

defined( 'ABSPATH' ) or die();


class Exception_base extends Exception
{

    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct( $message, $code, $previous );
    }


    public function __toString()
    {
        return __CLASS__ . ": [$this->code]: $this->message\n";
    }


    public function recoveryFunction()
    {
    }

}

