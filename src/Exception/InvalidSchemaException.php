<?php

declare(strict_types=1);

namespace shmolf\NotedHydrator\Exception;

use Exception;

class InvalidSchemaException extends Exception
{
    public function __construct(string $requestString)
    {
        $jsonString = stripslashes($requestString);
        $this->message = <<<ERROR
            \nClient Request has an incompatible JSON Schema:
                {$jsonString}
            ERROR;
    }
}
