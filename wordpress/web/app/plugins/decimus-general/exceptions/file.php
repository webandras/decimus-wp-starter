<?php

require_once 'exception.php';

class File_exception extends Exception_base
{
}


class File_open_exception extends File_exception
{
}


class File_close_exception extends File_exception
{
}
