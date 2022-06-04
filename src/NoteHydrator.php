<?php

declare(strict_types=1);

namespace shmolf\NotedHydrator;

use Exception;
use shmolf\NotedHydrator\Entity\NoteEntity;
use shmolf\NotedHydrator\Exception\InvalidSchemaException;
use shmolf\NotedHydrator\JsonSchema\BaseLibrary;
use Swaggest\JsonSchema\Schema;

class NoteHydrator
{
    private const GET = 'GET';
    private const POST = 'POST';

    public const REQ_API_VERSION = 'noted-client-api-version';
    public const REQ_NOTE_UPSERT = 'noted-client-upsert';

    private ?bool $isCompatible = null;
    private BaseLibrary $library;

    public function __construct(BaseLibrary $library)
    {
        $this->library = $library;
    }

    public function getHydratedNote(string $noteJson): ?NoteEntity
    {
        $this->validateSchema(
            $noteJson,
            $this->library->noteSchemaFilePath(),
        );

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
            'isCompatible' => $this->versionIsSupported(),
            'version' => $this->library->apiVersion,
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
        $this->isCompatible ??= in_array($this->library->apiVersion, $this->checkForBrowserSupport());
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
                $this->library->clientCompatibilitySchemaFilePath(),
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
