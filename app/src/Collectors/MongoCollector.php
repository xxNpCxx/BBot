<?php


namespace BBot\Collectors;


use BBot\ZMQSubscriber;
use MongoDB\Client;
use MongoDB\Collection;
use function json_decode;
use function microtime;
use function printf;
use function sprintf;

class MongoCollector extends ZMQSubscriber
{
    const DEFAULT_STORAGE_DSN = 'mongodb://mongodb:27017';
    const DEFAULT_MONGO_DATABASE = 'local';

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

    /**
     * @var array
     */
    private $collectedItemInfo;

    protected function onDataReceived(string $data)
    {
        $preparedDocument = $this->prepareDocumentToInsert($data);
        $this->collection->insertOne($preparedDocument);
        printf('.');
    }

    public function __construct(string $dataProviderEndpoint, array $collectedItemInfo = [])
    {
        parent::__construct($dataProviderEndpoint);
        $this->dbName = self::DEFAULT_MONGO_DATABASE;
        $this->collectedItemInfo = $collectedItemInfo;
        $this->setCollectionName();
        $this->connectToStorage();
        $this->collection = $this->storageClient->selectCollection($this->dbName, $this->collectionName);

    }

    private function prepareDocumentToInsert(string $data)
    {
        $jsonData = json_decode($data);
        $document = [
            't' => microtime(true),
            'data' => $jsonData
        ];
        return $document;
    }

    private function connectToStorage(string $dsn = self::DEFAULT_STORAGE_DSN, array $options = []): bool
    {
        $this->storageClient = new Client($dsn, $options);
        return true;
    }

    private function setCollectionName()
    {
        $mainSymbol = $this->collectedItemInfo['mainSymbol'] ?? 'undefinedMainSymbol';
        $quoteSymbol = $this->collectedItemInfo['quoteSymbol'] ?? 'undefinedQuoteSymbol';
        $exchangeName = $this->collectedItemInfo['exchange'] ?? 'undefinedExchange';
        $typeName = $this->collectedItemInfo['type'] ?? 'undefinedType';

        $this->collectionName = sprintf(
            '%s_%s_%s%s',
            $exchangeName,
            $typeName,
            $mainSymbol,
            $quoteSymbol
        );
    }

}