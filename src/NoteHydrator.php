<?php

declare(strict_types=1);

namespace shmolf\NotedRequestHandler;

use Opis\JsonSchema\Resolvers\SchemaResolver;
use Opis\JsonSchema\ValidationResult;
use Opis\JsonSchema\Validator;
use shmolf\NotedRequestHandler\Entity\NoteEntity;
use shmolf\NotedRequestHandler\Exception\InvalidSchemaException;
use shmolf\NotedRequestHandler\JsonSchema\Library;

class NoteHydrator
{
    private const GET = 'GET';
    private const POST = 'POST';

    public const REQ_API_VERSION = 'noted-client-api-version';
    public const REQ_NOTE_UPSERT = 'noted-client-upsert';

    private Validator $validator;
    private array $schemas;
    private ?bool $isCompatible = null;

    public function __construct()
    {
        $this->schemas = Library::getCurrent();
        $this->validator = new Validator();
        $resolver = $this->validator->resolver();

        if ($resolver instanceof SchemaResolver) {
            $resolver->registerFile(
                $this->schemas['note']['uri'],
                $this->schemas['note']['file']
            );
            $resolver->registerFile(
                $this->schemas['host-compatibility']['uri'],
                $this->schemas['host-compatibility']['file']
            );
            $resolver->registerFile(
                $this->schemas['client-compatibility']['uri'],
                $this->schemas['client-compatibility']['file']
            );
        }
    }

    public function getHydratedNote(): ?NoteEntity
    {
        $requestData = $this->getRequestValue(self::POST, self::REQ_NOTE_UPSERT, '');
        $schemaValidation = $this->validateSchema($requestData, $this->schemas['note']['uri']);

        if ($schemaValidation->hasError()) {
            /** @psalm-suppress PossiblyNullArgument since 'hasError' prevents null referencing **/
            throw new InvalidSchemaException($requestData, $schemaValidation->error());
        }

        $requestData = json_decode($requestData, true);
        $note = new NoteEntity();
        $note->title = $requestData['title'];
        $note->content = $requestData['content'];
        $note->setClientUuid($requestData['clientUuid']);

        if (!empty($requestData['noteUuid'])) {
            $note->setNoteUuid($requestData['noteUuid']);
        }

        if (!empty($requestData['tags'])) {
            $note->tags = $requestData['tags'];
        }

        return $note;
    }

    public function getCompatibilityJsonResponse(): string
    {
        return json_encode([
            'isCompatible' => $this->versionIsSupported(),
            'version' => Library::CUR_VERSION,
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
        $this->isCompatible = $this->isCompatible ?? in_array(Library::CUR_VERSION, $this->checkForBrowserSupport());
        return $this->isCompatible;
    }

    /**
     * This function should only be called as part of the browser's request to the server, to check API compatibility.
     *
     * @return int[]
     * @throws InvalidSchemaException
     */
    private function checkForBrowserSupport(): array
    {
        $requestData = (string)($this->getRequestValue(self::GET, self::REQ_API_VERSION, ''));
        $schemaValidation = $this->validateSchema($requestData, $this->schemas['client-compatibility']['uri']);

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

    /**
     * @param string $method
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    private function getRequestValue(string $method, string $key, $default = null)
    {
        switch ($method) {
            case self::GET:
                return $_GET[$key] ?? $default;
            case self::POST:
                return $_POST[$key] ?? $default;
            default:
                return $_REQUEST[$key] ?? $default;
        }
    }
}
