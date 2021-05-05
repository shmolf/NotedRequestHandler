<?php

declare(strict_types=1);

namespace shmolf\NotedHydrator\Exception;

use Exception;
use Opis\JsonSchema\Errors\ValidationError;

class InvalidSchemaException extends Exception
{
    public function __construct(string $requestString, ValidationError $error)
    {
        $jsonString = stripslashes($requestString);
        $this->message = <<<ERROR
            \nClient Request has an incompatible JSON Schema:
                {$jsonString}

            Validation Errors:
                {$error->message()}
            ERROR;
    }
}
