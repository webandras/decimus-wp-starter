<?php
require( "../vendor/autoload.php" );

$openapi = \OpenApi\Generator::scan( [ __DIR__ . '/../backend/API/Frontend' ] );
header( 'Content-Type: application/x-yaml' );
echo $openapi->toJson();