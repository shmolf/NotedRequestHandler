<?php

namespace shmolf\NotedRequestHandler\Exception;

use Exception;
use Opis\JsonSchema\Errors\ValidationError;

class InvalidSchemaException extends Exception
{
    public function __construct(string $requestString, ValidationError $error)
    {
        $this->message = <<<ERROR
            \nClient Request has an incompatible JSON Schema:
                {$requestString}

            Validation Errors:
                {$error->message()}
            ERROR;
    }
}
