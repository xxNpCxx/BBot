<?php


namespace BBot\Collectors;


use Exception;
use Ratchet\Client\Connector;
use Ratchet\Client\WebSocket;
use Ratchet\RFC6455\Messaging\MessageInterface;
use React\EventLoop\Factory;
use function json_encode;
use function strtolower;

class WebSocketCollector
{
    private const WS_URL = 'stream.binance.com';
    private const WS_PORT = 9443;
    private const WSPROTOCOL = 'wss';

    private $endpoint;

    private $mainSymbol;
    private $quoteSymbol;
    private $type;

    public function __construct(string $type, string $mainSymbol = null, string $quoteSymbol = null )
    {
        $this->type = $type;
        $this->mainSymbol = $mainSymbol;
        $this->quoteSymbol = $quoteSymbol;
        $this->setEndpoint();
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
                    echo "message: {$msg}\n";
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

//                $conn->send('Hello World!');
            }, function (Exception $e) use ($loop) {
                echo "Could not connect: {$e->getMessage()}\n";
                $loop->stop();
            });

        $loop->run();
    }

}