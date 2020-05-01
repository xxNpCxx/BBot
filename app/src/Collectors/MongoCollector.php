<?php


namespace BBot\Collectors;


use BBot\TCPSocketRoutes;
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

    const COLLECTED_ROUTES = [
        TCPSocketRoutes::ROUTE_BEST_BID_PRICE,
        TCPSocketRoutes::ROUTE_BEST_BID_QTY,
        TCPSocketRoutes::ROUTE_BEST_ASK_PRICE,
        TCPSocketRoutes::ROUTE_BEST_ASK_QTY,
    ];
    /**
     * @var Client
     */
    private $storageClient;
    private $dbName;

    /**
     * @var Collection[]
     */
    private $preparedCollections;

    /**
     * @var array
     */
    private $collectedItemInfo;

    protected function onDataReceived(string $route, string $data)
    {
        $preparedDocument = $this->prepareDocumentToInsert($data);
        $this->preparedCollections[$route]->insertOne($preparedDocument);
        printf('.');
    }

    public function __construct(string $dataProviderEndpoint, array $collectedItemInfo = [])
    {
        parent::__construct($dataProviderEndpoint, self::COLLECTED_ROUTES);
        $this->dbName = self::DEFAULT_MONGO_DATABASE;
        $this->collectedItemInfo = $collectedItemInfo;
        $this->connectToStorage();
        $this->prepareCollections();

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

    private function prepareCollections()
    {
        $mainSymbol = $this->collectedItemInfo['mainSymbol'] ?? 'undefinedMainSymbol';
        $quoteSymbol = $this->collectedItemInfo['quoteSymbol'] ?? 'undefinedQuoteSymbol';
        $exchangeName = $this->collectedItemInfo['exchange'] ?? 'undefinedExchange';
        $typeName = $this->collectedItemInfo['type'] ?? 'undefinedType';

        foreach (self::COLLECTED_ROUTES as $route) {
            $collectionName = sprintf(
                '%s_%s_%s%s_%s',
                $exchangeName,
                $typeName,
                $mainSymbol,
                $quoteSymbol,
                $route
            );
            $this->preparedCollections[$route] =  $this->storageClient->selectCollection($this->dbName, $collectionName);
        }
    }
}