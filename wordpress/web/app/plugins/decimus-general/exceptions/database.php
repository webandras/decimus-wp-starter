<?php

require_once 'exception.php';


class Decimus_query_exception extends Decimus_exception_base
{
}


class Decimus_delete_record_exception extends Decimus_query_exception
{
}


class Decimus_emptyDBTableException extends Decimus_query_exception
{
}


class Decimus_insert_record_exception extends Decimus_query_exception
{
}


class Decimus_permissions_exception extends Decimus_query_exception
{
}


class Decimus_update_record_exception extends Decimus_query_exception
{
}
