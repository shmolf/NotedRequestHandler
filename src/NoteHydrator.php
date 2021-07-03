<?php

declare(strict_types=1);

namespace shmolf\NotedHydrator;

use Exception;
use shmolf\NotedHydrator\Entity\NoteEntity;
use shmolf\NotedHydrator\Exception\InvalidSchemaException;
use shmolf\NotedHydrator\JsonSchema\Library;
use Swaggest\JsonSchema\Schema;

class NoteHydrator
{
    private const GET = 'GET';
    private const POST = 'POST';
    private const PUT = 'PUT';
    private const DELETE = 'DELETE';

    public const REQ_API_VERSION = 'noted-client-api-version';
    public const REQ_NOTE_UPSERT = 'noted-client-upsert';

    private array $schemas;
    private ?bool $isCompatible = null;

    public function __construct()
    {
        $this->schemas = Library::getCurrent();
    }

    public function getHydratedNote(string $noteJson): ?NoteEntity
    {
        try {
            $this->validateSchema(
                $noteJson,
                $this->schemas['note']['file']
            );
        } catch (Exception $e) {
            return null;
        }

        $requestData = json_decode($noteJson, true);
        $note = new NoteEntity();
        $note->title = $requestData['title'];
        $note->content = $requestData['content'];

        if (!empty($requestData['tags'])) {
            $note->tags = $requestData['tags'];
        }

        if (isset($requestData['inTrashcan'])) {
            $note->inTrashcan = $requestData['inTrashcan'];
        }

        if (isset($requestData['isDeleted'])) {
            $note->isDeleted = $requestData['isDeleted'];
        }

        return $note;
    }

    public function getCompatibilityJsonResponse(): string
    {
        return json_encode([
            'isCompatible' => $this->isCompatible ?? $this->versionIsSupported(),
            'version' => Library::CUR_VERSION,
        ]);
    }

    /**
     * Your application controller should would call this function, when the browser is checking compatibility.
     * Your controller should respond with a json response: `src/JsonSchema/v1/host-compatibility.json`
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

        try {
            $this->validateSchema(
                $requestData,
                $this->schemas['client-compatibility']['file']
            );
        } catch (Exception $e) {
            return [];
        }

        /** @psalm-suppress MixedArrayAccess since `['versions']` will be an array, per the schema **/
        return json_decode($requestData, true)['versions'];
    }

    private function validateSchema(string $jsonString, string $filePath): bool
    {
        $schemaValidator = Schema::import(json_decode(file_get_contents($filePath)));
        $schemaValidator->in(json_decode($jsonString));
        return true;
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
