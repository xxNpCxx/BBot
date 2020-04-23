<?php


namespace BBot\Providers;


use Exception;
use MongoDB\Client;
use Ratchet\Client\Connector;
use Ratchet\Client\WebSocket;
use Ratchet\RFC6455\Messaging\MessageInterface;
use React\EventLoop\Factory;
use ZMQ;
use ZMQContext;
use ZMQSocket;
use function json_decode;
use function strtolower;

class ExchangeDataProvider
{
    private const WS_URL = 'stream.binance.com';
    private const WS_PORT = 9443;
    private const WSPROTOCOL = 'wss';

    private $endpoint;

    private $mainSymbol;
    private $quoteSymbol;
    private $type;

    private $publisher;

    private $exchange;

    private $mongoClient;
    public function __construct(string $type, string $mainSymbol = null, string $quoteSymbol = null )
    {
        $this->publisher = new TCPSocketPublisher();
        $this->type = $type;
        $this->mainSymbol = $mainSymbol;
        $this->quoteSymbol = $quoteSymbol;
        $this->setEndpoint();

        $this->mongoClient = new Client(
            'mongodb://mongodb:27017',
            ['ssl' => false]
        );
    }

    public function setEndpoint(): void
    {
        $this->endpoint = self::WSPROTOCOL.'://'.self::WS_URL.':'.self::WS_PORT.'/ws/'. strtolower($this->mainSymbol . $this->quoteSymbol) . '@' . $this->type;
    }
    public function getEndpoint(): string
    {
        print_r($this->endpoint);
        echo(PHP_EOL);
        return $this->endpoint;
    }

    /**
     * @throws Exception
     */
    public function run()
    {

        $loop = Factory::create();
        $reactConnector = new \React\Socket\Connector($loop, [
            'dns' => '8.8.8.8',
            'timeout' => 2
        ]);
        $connector = new Connector($loop, $reactConnector);

        $headers = [];
        $subprotocols = [];
        $connector($this->getEndpoint(),$subprotocols, $headers)
            ->then(function (WebSocket $conn) {
                $conn->on('message', function (MessageInterface $msg) use ($conn) {
                    $this->publisher->send('Y',$msg);
                });

                $conn->on('error', function (MessageInterface $msg) use ($conn) {
                    echo "Error: {$msg}\n";
                });
                $conn->on('ping', function ($msg) use ($conn) {
                    echo "ping: {$msg}\n";
                });

                $conn->on('close', function ($code = null, $reason = null) {
                    echo "Connection closed ({$code} - {$reason})\n";
                });

            }, function (Exception $e) use ($loop) {
                echo "Could not connect: {$e->getMessage()}\n";
                $loop->stop();
            });

        $loop->run();
    }

}
