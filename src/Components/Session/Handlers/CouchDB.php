<?php

namespace Mamluk\Kipchak\Components\Session\Handlers;

use Illuminate\Http\Client\Factory;

/**
 * PHP SessionHandler for CouchDB
 */
class CouchDB implements \SessionHandlerInterface
{
    /**
     * @var string CouchDB user
     */
    private string $user;

    /**
     * @var string CouchDB password
     */
    private string $password;

    /**
     * @var string CouchDB host FQDN, with http:// or https://
     */
    private string $host;

    /**
     * @var int CouchDB Port
     */
    private int $port;

    /**
     * @var string Name of CouchDB Database to write sessions to
     */
    private string $apiDB;

    /**
     * @var array|null The object stored in CouchDB
     */
    private $data;

    /**
     * @var string A PHP time string. Example: '1 hour' or '2 days'
     */
    private string $maxlifetime;

    /**
     * @var Factory The Illuminate HTTP Client Factory
     */
    private Factory $client;

    /**
     * @var string The couch DB URL to make code less verbose
     */
    private string $url;

    public function __construct(string $maxlifetime, string $user, string $password, string $apiDB, string $host, int $port = 5984, Factory $httpClient = new Factory())
    {
        $this->user = $user;
        $this->password = $password;
        $this->host = $host;
        $this->port = $port;
        $this->apiDB = $apiDB;
        $this->data = null;
        $this->client = new $httpClient;
        $this->maxlifetime = $maxlifetime;
        $this->url = $this->host . ':' . $this->port . '/' . $this->apiDB;

        // Check if DB exists
        $existingDB = $this->client->withBasicAuth($this->user, $this->password)
            ->get($this->url);

        // If it does not exist, create the DB and design doc
        if (!$existingDB->ok()) {

            $dbCreated = $this->client->withBasicAuth($this->user, $this->password)
                ->put($this->url);

            if ($dbCreated->status() == 201) {
                // See README. Please create the couchdb database before hand and we don't want to try creating the database each time this class is loaded.
                $designdoc = json_encode([
                    '_id' => '_design/sessions',
                    'language' => 'javascript',
                    'views' => [
                        'gc' => [
                            'map' => 'function(doc) { emit(doc.expiry, doc._rev); }'
                        ]
                    ]
                ]);

                // Create Design Document
                $this->client->withBasicAuth($this->user, $this->password)
                    ->withBody($designdoc, 'application/json')
                    ->put($this->url . '/_design/sessions');
            }
        }
    }

    public function open($savePath = null, $sessionName = null): bool
    {
        return true;
    }

    public function close(): bool
    {
        return true;
    }

    public function read(string $id): string
    {
        $result = $this->client->withBasicAuth($this->user, $this->password)
            ->get($this->url . '/' . $id);

       if ($result->status() == 200) {
           $this->data = $result->json();
           return $result->json()['data'];

       }
       return '';
    }

    public function write($id, $data): bool
    {
        $updated = new \DateTime('now', new \DateTimeZone('UTC'));

        $body = [
            '_rev' => isset($this->data['_rev']) ? $this->data['_rev'] : '',
            'type' => isset($this->data['type']) ? $this->data['type'] : 'session',
            'data' => $data,
            'updated' => $updated->getTimestamp(),
            'updateReadable' => $updated->format(\DateTime::ATOM),
            'expiry' => $updated->modify('+'. $this->maxlifetime)->getTimestamp(),
            'expiryReadable' => $updated->format(\DateTime::ATOM),
        ];
        if (!isset($this->data['_rev'])) {
            unset($body['_rev']);
        }

        $result = $this->client->withBasicAuth($this->user, $this->password)
            ->withBody(json_encode($body), 'application/json')
            ->put($this->url . '/' . $id);

        return in_array($result->status(), [200, 201]);
    }

    public function destroy($id): bool
    {
        $result = $this->client->withBasicAuth($this->user, $this->password)
            ->get($this->url . '/' . $id);

        $this->client->withBasicAuth($this->user, $this->password)
            ->delete($this->url . '/' . $id . '?rev=' . $result->json()['_rev']);

        return true;
    }

    public function gc(int $maxlifetime): int
    {
        $time = (new \DateTime('now', new \DateTimeZone('UTC')))->getTimestamp();
        // Get documents who's expiry is less than $time
        $query =  "_design/sessions/_view/gc?endkey=$time";

        $expired = $this->client->withBasicAuth($this->user, $this->password)
            ->get($this->url . '/' . $query)
            ->json();

        foreach ($expired['rows'] as $session) {
            if (isset($session['id'])) {
                $r = $this->client->withBasicAuth($this->user, $this->password)
                    ->delete($this->host . ':' . $this->port . '/' .
                        $this->apiDB . '/' . $session['id'] . '?rev=' . $session['value']);
            }
        }

        if (count($expired['rows']) > 0) {
            return count($expired['rows']);
        }

        return false;

    }
}
