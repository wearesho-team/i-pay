<?php /** @noinspection PhpUnhandledExceptionInspection */

namespace Wearesho\Bobra\IPay\Tests\Unit;

use GuzzleHttp;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use phpmock\phpunit\PHPMock;
use PHPUnit\Framework\TestCase;
use Wearesho\Bobra\IPay;
use Wearesho\Bobra\Payments;

/**
 * Class ClientTest
 * @package Wearesho\Bobra\IPay\Tests\Unit
 */
class ClientTest extends TestCase
{
    use PHPMock;

    /** @var IPay\Config */
    protected $config;

    /** @var Payments\UrlPair */
    protected $urlPair;

    /** @var IPay\Transaction */
    protected $transaction;

    protected function setUp(): void
    {
        parent::setUp();
        $this->config = new IPay\Config(123456789, 'TEST_KEY', 'TEST_SECRET');
        $this->urlPair = new Payments\UrlPair('https://wearesho.com/good', 'https://wearesho.com/bad');
        $this->transaction = new IPay\Transaction(
            100,
            100.50,
            IPay\Transaction::TYPE_CHARGE,
            "Оплата услуг"
        );
        $this->getFunctionMock('Wearesho\\Bobra\\IPay', 'microtime')
            ->expects($this->any())->willReturn(1000);
    }

    /**
     * @expectedException \Wearesho\Bobra\IPay\ApiException
     * @expectedExceptionCode 4
     */
    public function testCreatePaymentError(): void
    {
        $errorResponse = '<?xml version="1.0" encoding="utf-8"?> <error>
<code>4</code>
<desc>Your sign or key is incorrect.</desc>
</error>';
        $mock = new MockHandler([
            new Response(400, [], $errorResponse)
        ]);

        $client = new IPay\Client(
            $this->config,
            new GuzzleHttp\Client(['handler' => GuzzleHttp\HandlerStack::create($mock)])
        );

        $client->createPayment($this->urlPair, $this->transaction);
    }

    /**
     * @expectedException \GuzzleHttp\Exception\ClientException
     */
    public function testInvalidErrorResponse(): void
    {
        $errorsResponse = '{NotXmlResponse}';
        $mock = new MockHandler([
            new Response(400, [], $errorsResponse),
        ]);
        $client = new IPay\Client(
            $this->config,
            new GuzzleHttp\Client(['handler' => GuzzleHttp\HandlerStack::create($mock)])
        );

        $client->createPayment(
            $this->urlPair,
            $this->transaction
        );
    }

    public function testCreatePayment(): void
    {
        $pid = 543;
        $url = 'https://secure.ipay.ua/fac795b9ffa93e0107a66db7a6a0716076c/';
        $salt = '4bd31cc81bf4a882ec19b3f4a2df9a8b1dd4694b';
        $sign = 'e49d266af9710fc455b4a6da239db8efe4a226bdf025a1e3b6dafb5699df0bc5d32c7a6e3bb160868e3c52c02c715251d89571beb6fde7210a9f108cf82b7955';
        $response = '<?xml version="1.0" encoding="utf-8"?> <payment>
<pid>' . $pid . '</pid> <url>' . $url . '</url> <status>1</status> <salt>' . $salt . '</salt> <sign>' . $sign . '</sign>
</payment>';
        $mock = new MockHandler([
            new Response(200, [], $response),
        ]);
        $client = new IPay\Client(
            $this->config,
            new GuzzleHttp\Client(['handler' => GuzzleHttp\HandlerStack::create($mock)])
        );

        $payment = $client->createPayment($this->urlPair, $this->transaction);
        $this->assertInstanceOf(IPay\Payment::class, $payment);
        /** @var IPay\Payment $payment */

        $this->assertEquals($payment->getId(), $pid);
        $this->assertEquals($payment->getUrl(), $url);
        $this->assertEquals($payment->getStatus(), 1);
    }

    /**
     * @expectedException \Wearesho\Bobra\IPay\InvalidSignException
     */
    public function testInvalidSign(): void
    {
        $pid = 543;
        $url = 'https://secure.ipay.ua/fac795b9ffa93e0107a66db7a6a0716076c/';
        $salt = '4bd31cc81bf4a882ec19b3f4a2df9a8b1dd4694b';
        $sign = 'invalidsign';
        $response = '<?xml version="1.0" encoding="utf-8"?> <payment>
<pid>' . $pid . '</pid> <url>' . $url . '</url> <status>1</status> <salt>' . $salt . '</salt> <sign>' . $sign . '</sign>
</payment>';
        $mock = new MockHandler([
            new Response(200, [], $response),
        ]);
        $client = new IPay\Client(
            $this->config,
            new GuzzleHttp\Client(['handler' => GuzzleHttp\HandlerStack::create($mock)])
        );

        try {
            $payment = $client->createPayment($this->urlPair, $this->transaction);
        } catch (IPay\InvalidSignException $exception) {
            $this->assertEquals($exception->getSalt(), $salt);
            $this->assertEquals($exception->getSign(), $sign);
            throw $exception;
        }
    }

    public function testRequestBody(): void
    {
        $pid = 543;
        $url = 'https://secure.ipay.ua/fac795b9ffa93e0107a66db7a6a0716076c/';
        $salt = '4bd31cc81bf4a882ec19b3f4a2df9a8b1dd4694b';
        $sign = 'e49d266af9710fc455b4a6da239db8efe4a226bdf025a1e3b6dafb5699df0bc5d32c7a6e3bb160868e3c52c02c715251d89571beb6fde7210a9f108cf82b7955';
        $response = '<?xml version="1.0" encoding="utf-8"?> <payment>
<pid>' . $pid . '</pid> <url>' . $url . '</url> <status>1</status> <salt>' . $salt . '</salt> <sign>' . $sign . '</sign>
</payment>';

        $container = [];
        $history = GuzzleHttp\Middleware::history($container);
        $mock = new MockHandler([
            new Response(200, [], $response),
            new Response(200, [], $response),
            new Response(200, [], $response),
        ]);
        $stack = GuzzleHttp\HandlerStack::create($mock);
        $stack->push($history);

        $client = new IPay\Client(
            $this->config,
            new GuzzleHttp\Client(['handler' => $stack,])
        );

        $transaction = $this->transaction;
        $transaction->setFee(50.25);
        $transaction->setNote("Заметка");
        $transaction->setInfo([
            'author' => 'Wearesho',
        ]);

        $client->createPayment($this->urlPair, $transaction);
        $client->completePayment(10);
        $client->reversePayment(11);

        $requests = [
            // create payment request
            '<?xml version="1.0" encoding="utf-8"?>
<payment><auth><mch_id>123456789</mch_id><salt>e3cbba8883fe746c6e35783c9404b4bc0c7ee9eb</salt><sign>a1e06298be043bd84a9412d71739488e262d3d73068d674fa67a8be207b468aba27f2b6211a6f46d4397de57aefd278f565fa6765adef50a46ff6e86feba7d50</sign></auth><urls><good>https://wearesho.com/good</good><bad>https://wearesho.com/bad</bad></urls><transactions><transaction><mch_id>123456789</mch_id><srv_id>100</srv_id><type>11</type><amount>10050</amount><currency>UAH</currency><desc>Оплата услуг</desc><info>{"author":"Wearesho"}</info><node>Заметка</node><fee>5025</fee></transaction></transactions><lifetime>24</lifetime><version>3.00</version><lang>ua</lang></payment>',
            '<?xml version="1.0" encoding="utf-8"?>
<payment><auth><mch_id>123456789</mch_id><salt>e3cbba8883fe746c6e35783c9404b4bc0c7ee9eb</salt><sign>a1e06298be043bd84a9412d71739488e262d3d73068d674fa67a8be207b468aba27f2b6211a6f46d4397de57aefd278f565fa6765adef50a46ff6e86feba7d50</sign></auth><pid>10</pid><action>complete</action><version>3.00</version></payment>',
            '<?xml version="1.0" encoding="utf-8"?>
<payment><auth><mch_id>123456789</mch_id><salt>e3cbba8883fe746c6e35783c9404b4bc0c7ee9eb</salt><sign>a1e06298be043bd84a9412d71739488e262d3d73068d674fa67a8be207b468aba27f2b6211a6f46d4397de57aefd278f565fa6765adef50a46ff6e86feba7d50</sign></auth><pid>11</pid><action>reversal</action><version>3.00</version></payment>',
        ];

        foreach ($container as $key => $item) {
            /** @var GuzzleHttp\Psr7\Request $request */
            $request = $item['request'];
            $body = $request->getBody();
            $this->assertStringStartsWith('data=', $body);
            $xml = trim(urldecode(substr($body, 5)));
            $this->assertArrayHasKey($key, $requests);
            $expected = $requests[$key];
            $this->assertEquals($expected, $xml);
        }
    }
}
