<?php /** @noinspection PhpUnhandledExceptionInspection */

namespace Wearesho\Bobra\IPay\Tests\Unit\Notification;

use PHPUnit\Framework\TestCase;
use Wearesho\Bobra\IPay;
use Wearesho\Bobra\Payments\TransactionCollection;

/**
 * Class ServerTest
 * @package Wearesho\Bobra\IPay\Tests\Unit\Notification
 */
class ServerTest extends TestCase
{
    /**
     * @expectedException \Wearesho\Bobra\IPay\Notification\UnsupportedMerchantException
     * @expectedExceptionMessage Unsupported MerchantId 7
     */
    public function testUnsupportedMerchant()
    {
        $server = new IPay\Notification\Server(new IPay\Notification\ConfigProvider());
        // phpcs:disable
        $xml = '<?xml version="1.0" encoding="utf-8"?> <payment id="143">
<ident>520edda7b4e6e20482a30c85c44a1e56d8e8a666</ident> <status>5</status>
<amount>5000</amount>
<currency>UAH</currency> <timestamp>1312201619</timestamp>
<transactions> <transaction id="431">
<mch_id>7</mch_id> <srv_id>1</srv_id> <amount>5077</amount> <currency>UAH</currency> <type>10</type> <status>11</status> <code>00</code>
<desc>Оплата услуг</desc> <info>{"dogovor":3512313424}</info>
</transaction> <transaction id="432">
<mch_id>7</mch_id> <srv_id>1</srv_id> <amount>5077</amount> <currency>UAH</currency> <type>11</type> <status>11</status> <code>00</code>
<desc>Оплата услуг</desc> <info>{"dogovor":3512313424}</info>
           </transaction>
      </transactions>
<salt>4bd31cc81bf4a882ec19b3f4a2df9a8b1dd4694b</salt> <sign>78f1022cb8ffbdcfa0997a5e72...0f324424eb4d2fbffcf21c7426bafe0</sign>
</payment>';
        // phpcs:enable
        $server->handle($xml);
    }

    /**
     * @expectedException \Wearesho\Bobra\IPay\Notification\InvalidBodyException
     * @expectedExceptionMessage Missing ID
     */
    public function testInvalidPayment()
    {
        $server = new IPay\Notification\Server(new IPay\Notification\ConfigProvider());
        $xml = '<?xml version="1.0" encoding="utf-8"?> <payment>
<ident>520edda7b4e6e20482a30c85c44a1e56d8e8a666</ident> <status>5</status></payment>';
        try {
            $server->handle($xml);
        } catch (IPay\Notification\InvalidBodyException $exception) {
            $this->assertEquals($xml, $exception->getXml());
            throw $exception;
        }
    }

    /**
     * @expectedException \Wearesho\Bobra\IPay\Notification\InvalidBodyException
     * @expectedExceptionMessage Invalid XML
     */
    public function testInvalidXml()
    {
        $server = new IPay\Notification\Server(new IPay\Notification\ConfigProvider());
        $server->handle('{"json": true}');
    }

    /**
     * @expectedException \Wearesho\Bobra\IPay\Notification\InvalidBodyException
     * @expectedExceptionMessage Transaction Info: Syntax error
     */
    public function testInvalidTransactionInfo()
    {
        $server = new IPay\Notification\Server(new IPay\Notification\ConfigProvider());
        // phpcs:disable
        $xml = '<?xml version="1.0" encoding="utf-8"?> <payment id="143">
<ident>520edda7b4e6e20482a30c85c44a1e56d8e8a666</ident> <status>5</status>
<amount>5000</amount>
<currency>UAH</currency> <timestamp>1312201619</timestamp>
<transactions> <transaction id="431">
<mch_id>7</mch_id> <srv_id>1</srv_id> <amount>5077</amount> <currency>UAH</currency> <type>10</type> <status>11</status> <code>00</code>
<desc>Оплата услуг 1</desc> <info>notValidJson2oh48{</info>
</transaction>
      </transactions>
<salt>4bd31cc81bf4a882ec19b3f4a2df9a8b1dd4694b</salt> <sign>77da9e174a47dc83453db78c38fcd2937b4ac9f4dcb42ea202bf1087e9f86b95d30154fc1f9da395fe73104bc60d45c6f256c86914d26af595e659076b33c8b3</sign>
</payment>';
        // phpcs:enable
        $server->handle($xml);
    }

    /**
     * @expectedException \Wearesho\Bobra\IPay\Notification\InvalidBodyException
     * @expectedExceptionMessage Missing transactions
     */
    public function testMissingTransactions()
    {
        $server = new IPay\Notification\Server(new IPay\Notification\ConfigProvider());
        // phpcs:disable
        $xml = '<?xml version="1.0" encoding="utf-8"?> <payment id="143">
<ident>520edda7b4e6e20482a30c85c44a1e56d8e8a666</ident> <status>5</status>
<amount>5000</amount>
<currency>UAH</currency> <timestamp>1312201619</timestamp>
<salt>4bd31cc81bf4a882ec19b3f4a2df9a8b1dd4694b</salt> <sign>77da9e174a47dc83453db78c38fcd2937b4ac9f4dcb42ea202bf1087e9f86b95d30154fc1f9da395fe73104bc60d45c6f256c86914d26af595e659076b33c8b3</sign>
</payment>';
        // phpcs:disable
        $server->handle($xml);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Merchant Id can not be found
     */
    public function testWithoutMerchantId()
    {
        $server = new IPay\Notification\Server(new IPay\Notification\ConfigProvider());
        // phpcs:disable
        $xml = '<?xml version="1.0" encoding="utf-8"?> <payment id="143">
<ident>520edda7b4e6e20482a30c85c44a1e56d8e8a666</ident> <status>5</status>
<amount>5000</amount>
<currency>UAH</currency> <timestamp>1312201619</timestamp>
<transactions> <transaction id="431">
<srv_id>1</srv_id> <amount>5077</amount> <currency>UAH</currency> <type>10</type> <status>11</status> <code>00</code>
<desc>Оплата услуг 1</desc> <info>{"dogovor":3512313424}</info>
</transaction>
      </transactions>
<salt>4bd31cc81bf4a882ec19b3f4a2df9a8b1dd4694b</salt> <sign>77da9e174a47dc83453db78c38fcd2937b4ac9f4dcb42ea202bf1087e9f86b95d30154fc1f9da395fe73104bc60d45c6f256c86914d26af595e659076b33c8b3</sign>
</payment>';
        // phpcs:enable
        $server->handle($xml);
    }

    public function testPayment()
    {
        $config = new IPay\Config(7, 'test-secret', 'test-key');
        $server = new IPay\Notification\Server(new IPay\Notification\ConfigProvider([$config]));
        // phpcs:disable
        $xml = '<?xml version="1.0" encoding="utf-8"?> <payment id="143">
<ident>520edda7b4e6e20482a30c85c44a1e56d8e8a666</ident> <status>5</status>
<amount>5000</amount>
<currency>UAH</currency> <timestamp>1312201619</timestamp>
<transactions> <transaction id="431">
<mch_id>7</mch_id> <srv_id>1</srv_id> <amount>5077</amount> <currency>UAH</currency> <type>10</type> <status>11</status> <code>00</code>
<desc>Оплата услуг 1</desc> <info>{"dogovor":3512313424}</info>
</transaction> <transaction id="432">
<mch_id>7</mch_id> <srv_id>1</srv_id> <amount>5077</amount> <currency>UAH</currency> <type>11</type> <status>11</status> <code>00</code>
<desc>Оплата услуг 2</desc> <info>{"dogovor":3512313424}</info>
           </transaction>
      </transactions>
<salt>4bd31cc81bf4a882ec19b3f4a2df9a8b1dd4694b</salt> <sign>77da9e174a47dc83453db78c38fcd2937b4ac9f4dcb42ea202bf1087e9f86b95d30154fc1f9da395fe73104bc60d45c6f256c86914d26af595e659076b33c8b3</sign>
</payment>';
        $sign = '77da9e174a47dc83453db78c38fcd2937b4ac9f4dcb42ea202bf1087e9f86b95d30154fc1f9da395fe73104bc60d45c6f256c86914d26af595e659076b33c8b3';
        // phpcs:enable

        $payment = $server->handle($xml);
        $this->assertEquals(
            new IPay\Notification\Payment(
                143,
                '520edda7b4e6e20482a30c85c44a1e56d8e8a666',
                5,
                5000,
                'UAH',
                new \DateTime('2011-08-01 12:26:59.000000'),
                new TransactionCollection([
                    (new IPay\Transaction(
                        431,
                        50.77,
                        'Оплата услуг 1',
                        '10',
                        [
                            'dogovor' => 3512313424,
                        ],
                        'UAH'
                    ))->setMerchantId(7),
                    (new IPay\Transaction(
                        432,
                        50.77,
                        'Оплата услуг 2',
                        '11',
                        [
                            'dogovor' => 3512313424,
                        ],
                        'UAH'
                    ))->setMerchantId(7),
                ]),
                '4bd31cc81bf4a882ec19b3f4a2df9a8b1dd4694b',
                $sign
            ),
            $payment
        );
    }
}
