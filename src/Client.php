<?php

namespace Wearesho\Bobra\IPay;

use GuzzleHttp;
use Wearesho\Bobra\Payments;

/**
 * Class Client
 * @package Wearesho\Bobra\IPay
 */
class Client implements Payments\ClientInterface
{
    public const ACTION_COMPLETE = 'complete';
    public const ACTION_REVERSAL = 'reversal';

    /** @var ConfigInterface */
    protected $config;

    /** @var GuzzleHttp\ClientInterface */
    protected $client;

    public function __construct(ConfigInterface $config, GuzzleHttp\ClientInterface $client)
    {
        $this->config = $config;
        $this->client = $client;
    }

    /**
     * @param Payments\UrlPairInterface $pair
     * @param TransactionInterface|Payments\TransactionInterface $transaction
     * @return Payments\PaymentInterface
     * @throws ApiException
     * @throws GuzzleHttp\Exception\GuzzleException
     * @throws InvalidSignException
     */
    public function createPayment(
        Payments\UrlPairInterface $pair,
        Payments\TransactionInterface $transaction
    ): Payments\PaymentInterface {
        return $this->createPaymentMultiple($pair, [$transaction]);
    }

    /**
     * @param Payments\UrlPairInterface $pair
     * @param TransactionInterface[]|Payments\TransactionInterface[] $transactions
     * @return Payment
     * @throws ApiException
     * @throws GuzzleHttp\Exception\GuzzleException
     * @throws InvalidSignException
     */
    public function createPaymentMultiple(Payments\UrlPairInterface $pair, array $transactions): Payment
    {
        $request = [
            'auth' => $this->requestAuth(),
            'urls' => $this->convertUrlPairToArray($pair),
            'transactions' => array_map([$this, 'convertTransactionToArray'], $transactions),
            'lifetime' => $this->config->getLifetime(),
            'version' => $this->config->getVersion(),
            'lang' => $this->config->getLanguage(),
        ];

        return $this->request($request);
    }

    /**
     * @param int $paymentId
     * @return Payment
     * @throws InvalidSignException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws ApiException
     */
    public function reversePayment(int $paymentId): Payment
    {
        return $this->completePayment($paymentId, Client::ACTION_REVERSAL);
    }

    /**
     * @param int $paymentId
     * @param string $action
     * @return Payment
     * @throws InvalidSignException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws ApiException
     */
    public function completePayment(int $paymentId, string $action = Client::ACTION_COMPLETE): Payment
    {
        $request = [
            'auth' => $this->requestAuth(),
            'pid' => $paymentId,
            'action' => $action,
            'version' => $this->config->getVersion(),
        ];
        return $this->request($request);
    }

    private function convertUrlPairToArray(Payments\UrlPairInterface $pair): array
    {
        return [
            'good' => $pair->getGood(),
            'bad' => $pair->getBad(),
        ];
    }

    private function convertTransactionToArray(Payments\TransactionInterface $transaction): array
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

        if ($transaction instanceof TransactionInterface) {
            $note = $transaction->getNote();
            if (!is_null($note)) {
                $array['node'] = $note;
            }

            $fee = $transaction->getFee();
            if (!is_null($fee)) {
                $array['fee'] = $fee;
            }
        }

        return $array;
    }

    private function requestAuth(): array
    {
        $salt = sha1(microtime(true));

        return [
            'mch_id' => $this->config->getId(),
            'salt' => $salt,
            'sign' => hash_hmac('sha512', $salt, $this->config->getSecret()),
        ];
    }

    /**
     * @param array $data
     * @return Payment
     * @throws InvalidSignException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws ApiException
     */
    private function request(array $data): Payment
    {
        try {
            $response = $this->client->request('post', $this->config->getUrl(), [
                'form_params' => [
                    'data' => $this->toXml($data),
                ],
            ]);
        } catch (GuzzleHttp\Exception\RequestException $exception) {
            $body = (string)$exception->getResponse()->getBody();
            if (!preg_match('/<error>(.*)/', $body)) {
                throw $exception;
            }
            $xml = simplexml_load_string($body);
            throw new ApiException((int)$xml->code);
        }

        $this->checkResponseSign((string)$response->getBody());
        $object = simplexml_load_string((string)$response->getBody());

        return new Payment(
            (int)$object->pid,
            (string)$object->url,
            (int)$object->status
        );
    }


    /**
     * @param string $xml
     * @throws InvalidSaltOrSignException
     * @throws InvalidSignException
     */
    private function checkResponseSign(string $xml): void
    {
        if ($xml === 'incorrect salt or sign') {
            throw new InvalidSaltOrSignException();
        }

        preg_match('|\<salt\>(.*?)\<\/salt\>|ism', $xml, $res);

        $salt = $res[1];

        preg_match('|\<sign\>(.*?)\<\/sign\>|ism', $xml, $res);

        $sign = $res[1];

        if (hash_hmac('sha512', $salt, $this->config->getKey()) !== $sign) {
            throw new InvalidSignException(
                $sign,
                $salt,
                "Invalid sign from response"
            );
        }
    }

    /**
     * @param $data
     * @param string $rootNodeName
     * @param \SimpleXMLElement|null $xml
     * @return string
     */
    private function toXml($data, $rootNodeName = 'payment', \SimpleXMLElement $xml = null): string
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
                static::toXml($value, $rootNodeName, $node);
            } else {
                $value = trim($value);
                $xml->addChild($key, $value);
            }
        }
        return $xml->asXML();
    }
}
