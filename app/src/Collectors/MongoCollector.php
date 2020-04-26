<?php


namespace BBot\Collectors;


use MongoDB\Client;
use MongoDB\Collection;
use function json_decode;
use function printf;

class MongoCollector extends TCPSocketSubscriber
{
    const DEFAULT_STORAGE_DSN = 'mongodb://mongodb:27017';
    const DEFAULT_MONGO_DATABASE = 'local';
    const DEFAULT_MONGO_COLLECTION = 'binance-price';

    /**
     * @var Client
     */
    private $storageClient;
    private $dbName;
    private $collectionName;
    /**
     * @var Collection
     */
    private $collection;

    private $collectingIdentityName;

    protected function onDataReceived(string $data)
    {
        $jsonData = json_decode($data);
        $this->collection->insertOne([$this->collectingIdentityName => $jsonData]);
        printf('.');
    }

    public function __construct(string $dataProviderEndpoint, string $collectingIdentityName = null)
    {
        parent::__construct($dataProviderEndpoint);

        $this->dbName = self::DEFAULT_MONGO_DATABASE;
        $this->collectionName = self::DEFAULT_MONGO_COLLECTION;

        $this->collectingIdentityName = $collectingIdentityName;
        $this->connectToStorage();
        $this->collection = $this->storageClient->selectCollection($this->dbName, $this->collectionName);

    }

    private function connectToStorage(string $dsn = self::DEFAULT_STORAGE_DSN, array $options = []): bool
    {
        $this->storageClient = new Client($dsn, $options);
        return true;
    }

}