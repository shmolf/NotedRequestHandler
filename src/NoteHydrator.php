<?php

declare(strict_types=1);

namespace shmolf\NotedRequestHandler;

use Opis\JsonSchema\Resolvers\SchemaResolver;
use Opis\JsonSchema\ValidationResult;
use Opis\JsonSchema\Validator;
use shmolf\NotedRequestHandler\Exceptions\InvalidSchemaException;

class NoteHydrator
{
    private const NOTE_SCHEMA_URI = 'https://note-d.app/schema/note.json';
    private const NOTE_SCHEMA_FILE = './src/JsonSchemas/note.json';
    private const COMPATIBILITY_SCHEMA_URI = 'https://note-d.app/schema/compatibility.json';
    private const COMPATIBILITY_SCHEMA_FILE = './src/JsonSchemas/compatibility.json';
    private const CLIENT_COMPATIBILITY_SCHEMA_URI = 'https://note-d.app/schema/client-compatibility.json';
    private const CLIENT_COMPATIBILITY_SCHEMA_FILE = './src/JsonSchemas/client-compatibility.json';
    public const API_VERSION = 1; // This should be versioned when the request/response schemas change
    public const CLIENT_VERSION_REQ_KEY = 'noted-client-api-version';

    private Validator $validator;
    private ?bool $isCompatible = null;

    public function __construct()
    {
        $this->validator = new Validator();
        $resolver = $this->validator->resolver();

        if ($resolver instanceof SchemaResolver) {
            $resolver->registerFile(self::NOTE_SCHEMA_URI, self::NOTE_SCHEMA_FILE);
            $resolver->registerFile(self::COMPATIBILITY_SCHEMA_URI, self::COMPATIBILITY_SCHEMA_FILE);
            $resolver->registerFile(self::CLIENT_COMPATIBILITY_SCHEMA_URI, self::CLIENT_COMPATIBILITY_SCHEMA_FILE);
        }
    }

    public function getCompatibilityJsonResponse(): string
    {
        return json_encode([
            'isCompatible' => $this->versionIsSupported(),
            'version' => self::API_VERSION,
        ]);
    }

    /**
     * Your application controller should would call this function, when the browser makes a request to
     * GET `your-host/api/v~/note/compatibility`
     *
     * Your controller should respond with a json response: `src/JsonSchemas/compatibility.json`
     *
     * @return bool
     */
    public function versionIsSupported(): bool
    {
        $this->isCompatible = $this->isCompatible ?? in_array(self::API_VERSION, $this->checkForBrowserSupport());
        return $this->isCompatible;
    }

    /**
     * This function should only be called as part of the browser's request to the server, to check API compatibility.
     *
     * @return array<array-key, int>
     * @throws InvalidSchemaException
     */
    private function checkForBrowserSupport(): array
    {
        $requestData = (string)($_GET[self::CLIENT_VERSION_REQ_KEY] ?? '');
        $schemaValidation = $this->validateSchema($requestData, self::CLIENT_COMPATIBILITY_SCHEMA_URI);

        if ($schemaValidation->hasError()) {
            /** @psalm-suppress PossiblyNullArgument since 'hasError' prevents null referencing **/
            throw new InvalidSchemaException($requestData, $schemaValidation->error());
        }

        /** @psalm-suppress MixedArrayAccess since `['versions']` will be an array, per the schema **/
        return $schemaValidation->isValid() ? json_decode($requestData, true)['versions'] : [];
    }

    private function validateSchema(string $jsonString, string $uri): ValidationResult
    {
        $resolver = $this->validator->resolver();

        // We'll check if the $resolver is set, otherwise, we'll manully load the file.
        return $resolver instanceof SchemaResolver
            ? $this->validator->validate(json_decode($jsonString), $uri)
            : $this->validator->validate(json_decode($jsonString), file_get_contents('./src/JsonSchemas/note.json'));
    }
}
