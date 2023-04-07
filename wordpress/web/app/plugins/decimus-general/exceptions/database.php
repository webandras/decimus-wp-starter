<?php

require_once 'exception.php';


class Query_exception extends Exception_base
{
}


class Delete_record_exception extends Query_exception
{
}


class EmptyDBTableException extends Query_exception
{
}


class Insert_record_exception extends Query_exception
{
}


class Permissions_exception extends Query_exception
{
}


class Update_record_exception extends Query_exception
{
}
