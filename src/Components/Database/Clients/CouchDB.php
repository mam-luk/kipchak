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

        $this->createDatabase();
    }

    /**
     * Creates the database if it does not exist
     * @return bool
     */
    protected function createDatabase(): bool
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
    public function create(string $id, string $document)
    {
        $r = $this->http->withBasicAuth($this->user, $this->password)
            ->withBody($document, 'application/json')
            ->put($this->url . '/' . $id);

        return $r->status() === 201;
    }

    /**
     * @param string $id
     * @param string $document JSON encoded string
     * @return void
     */
    public function update(string $id, string $document, string $revision = null)
    {
        if ($revision === null) {
            $r = $this->http->withBasicAuth($this->user, $this->password)
                ->get($this->url . '/' . $id);
            if ($r->ok()) {
                $revision = $r->json()['_rev'];
            }
        }

        $r = $this->http->withBasicAuth($this->user, $this->password)
            ->withBody($document, 'application/json')
            ->put($this->url . '/' . $id . '?rev=' . $revision);

        return $r->status() === 201;
    }


    public function read(string $id): array
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
    public function delete(string $id, string $revision = null): bool
    {
        if ($revision === null) {
            $r = $this->http->withBasicAuth($this->user, $this->password)
                ->get($this->url . '/' . $id);
            if ($r->ok()) {
                $revision = $r->json()['_rev'];
            }
        }

        $r = $this->http->withBasicAuth($this->user, $this->password)
            ->delete($this->url . '/' . $id . '?rev=' . $revision);

        return $r->ok();
    }
}