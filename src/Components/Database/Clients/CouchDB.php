<?php

namespace Mamluk\Kipchak\Components\Database\Clients;

use Illuminate\Http\Client\Factory;

class CouchDB
{
    private $url;
    private $http;
    private $database;
    private $user;
    private $password;
    private $host;
    private $port;


    public function __construct(string $user, string $password, string $database, string $host, int $port = 5984)
    {
        $this->http = new Factory();
        $this->database = $database;
        $this->host = $host;
        $this->port = $port;
        $this->url = "$host:$port/$database";
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Creates the database if it does not exist
     * @return bool
     */
    public function createDatabase(): bool
    {
        $existingDB = $this->http->withBasicAuth($this->user, $this->password)
            ->get($this->url);
        if (!$existingDB->ok()) {
            $dbCreated = $this->http->withBasicAuth($this->user, $this->password)
                ->put($this->url);

            return $dbCreated->status() === 201;
        }

        return true;
    }

    /**
     * @param string $id
     * @param string $document JSON encoded string
     * @return void
     */
    public function createDocument(string $id, string $document)
    {
        $r = $this->http->withBasicAuth($this->user, $this->password)
            ->withBody($document, 'application/json')
            ->put($this->url . '/' . $id);

        return $r->status() === 201;
    }

    /**
     * @param string $name Name of the design document without the _design/ prefix
     * @param string $document
     * @return bool
     */
    public function createDesignDocument(string $name, string $document)
    {
        $r = $this->http->withBasicAuth($this->user, $this->password)
            ->withBody($document, 'application/json')
            ->put($this->url . '/_design/' .$name );

        return $r->status() === 201;
    }

    public function getDocument(string $id): array
    {
        $r = $this->http->withBasicAuth($this->user, $this->password)
            ->get($this->url . '/' . $id);
        if ($r->ok()) {
            return $r->json();
        }

        return [];
    }

    /**
     * @param string $id
     * @param string|null $revision _rev from the document. If not provided, the document will be queried to get this value
     * @return bool
     */
    public function deleteDocument(string $id, string $revision = null): bool
    {
        if ($revision === null) {
            $r = $this->http->withBasicAuth($this->user, $this->password)
                ->get($this->url . '/' . $id);
            if ($r->ok()) {
                $revision = $r->json()['_rev'];
            }
        }

        $r = $this->http->withBasicAuth($this->user, $this->password)
            ->delete($this->url . '?rev=' . $revision);

        return $r->ok();
    }
}