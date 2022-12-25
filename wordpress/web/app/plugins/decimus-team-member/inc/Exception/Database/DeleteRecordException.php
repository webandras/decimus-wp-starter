<?php

namespace Gulacsi\TeamMember\Exception\Database;

use Exception;

defined('ABSPATH') or die();

// subtype of db query exception
class DeleteRecordException extends DBQueryException
{
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [$this->code]: $this->message\n";
    }

    public function recoveryFunction()
    {
    }
}
