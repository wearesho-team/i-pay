<?php

namespace Wearesho\Bobra\IPay;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Client
 * @package Wearesho\Bobra\IPay
 */
class Client
{
    public const ACTION_COMPLETE = 'complete';
    public const ACTION_REVERSAL = 'reversal';

    /** @var ConfigInterface */
    protected $config;

    /** @var ClientInterface */
    protected $client;

    public function __construct(ConfigInterface $config, ClientInterface $client)
    {
        $this->config = $config;
    }

    /**
     * @param UrlPair $url
     * @param TransactionInterface|TransactionInterface[] $transactions
     * @return string
     * @throws InvalidSignException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createPayment(UrlPair $url, $transactions)
    {
        $request = [
            'auth' => $this->_requestAuth(),
            'urls' => $this->_convertUrlPairToArray($url),
            'transactions' => array_map([$this, '_convertTransactionToArray'], (array)$transactions),
            'lifetime' => $this->config->getLifetime(),
            'version' => $this->config->getVersion(),
            'lang' => $this->config->getLanguage(),
        ];

        return $this->_request($request);
    }

    /**
     * @param int $paymentId
     * @return string
     * @throws InvalidSignException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function reversePayment(int $paymentId): string
    {
        return $this->completePayment($paymentId, Client::ACTION_REVERSAL);
    }

    /**
     * @param int $paymentId
     * @param string $action
     * @return mixed
     * @throws InvalidSignException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function completePayment(int $paymentId, string $action = Client::ACTION_COMPLETE): string
    {
        $request = [
            'auth' => $this->_requestAuth(),
            'pid' => $paymentId,
            'action' => $action,
            'version' => $this->config->getVersion(),
        ];
        return $this->_request($request);
    }

    private function _convertUrlPairToArray(UrlPair $pair): array
    {
        return [
            'good' => $pair->getGood(),
            'bad' => $pair->getBad(),
        ];
    }

    private function _convertTransactionToArray(TransactionInterface $transaction): array
    {
        $array = [
            'mch_id' => $this->config->getId(),
            'srv_id' => $transaction->getService(),
            'type' => $transaction->getType(),
            'amount' => $transaction->getAmount(),
            'currency' => $transaction->getCurrency(),
            'desc' => $transaction->getDescription(),
            'info' => json_encode($transaction->getInfo()),
        ];

        $note = $transaction->getNote();
        if (!is_null($note)) {
            $array['node'] = $note;
        }

        $fee = $transaction->getFee();
        if (!is_null($fee)) {
            $array['fee'] = $fee;
        }

        return $array;
    }


    private function _requestAuth(): array
    {
        $salt = sha1(microtime(true));

        return [
            'mch_id' => $this->config->getId(),
            'salt' => $salt,
            'sign' => hash_hmac('sha512', $salt, $this->config->getKey()),
        ];
    }

    /**
     * @param array $data
     * @return ResponseInterface
     * @throws InvalidSignException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function _request(array $data): ResponseInterface
    {
        $response = $this->client->request('post', $this->config->getUrl(), [
            'form_params' => [
                'data' => $this->_toXml($data),
            ],
        ]);

        $this->_checkResponseSign((string)$response->getBody());
        return $response;
    }


    /**
     * @param $xml
     * @throws InvalidSignException
     */
    private function _checkResponseSign(string $xml): void
    {
        preg_match('|\<salt\>(.*?)\<\/salt\>|ism', $xml, $res);

        $salt = $res[1];

        preg_match('|\<sign\>(.*?)\<\/sign\>|ism', $xml, $res);

        $sign = $res[1];

        if (hash_hmac('sha512', $salt, $this->config->getSecret()) !== $sign) {
            throw new InvalidSignException(
                $sign, $salt, "Invalid sign from response"
            );
        }
    }

    /**
     * @param $data
     * @param string $rootNodeName
     * @param \SimpleXMLElement|null $xml
     * @return string
     */
    private function _toXml($data, $rootNodeName = 'payment', \SimpleXMLElement $xml = null): string
    {
        if ($xml == null) {
            $xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$rootNodeName />");
        }

        foreach ($data as $key => $value) {
            if (is_numeric($key)) {
                $key = "transaction";
            }

            if (is_array($value)) {
                $node = $xml->addChild($key);
                static::_toXml($value, $rootNodeName, $node);
            } else {
                $value = trim($value);
                $xml->addChild($key, $value);
            }

        }
        return $xml->asXML();
    }
}
