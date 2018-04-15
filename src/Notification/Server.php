<?php

namespace Wearesho\Bobra\IPay\Notification;

use Wearesho\Bobra\IPay;
use Wearesho\Bobra\Payments;

/**
 * Class Server
 * @package Wearesho\Bobra\IPay\Notification
 */
class Server
{
    /** @var ConfigProviderInterface */
    protected $configProvider;

    public function __construct(ConfigProviderInterface $configProvider)
    {
        $this->configProvider = $configProvider;
    }

    /**
     * @param string $xml
     * @throws IPay\InvalidSignException
     * @throws InvalidBodyException
     * @return Payment
     */
    public function handle(string $xml): Payment
    {
        $payment = $this->parseXml($xml);

        try {
            $merchantId = $this->getMerchantId($payment->getTransactions());
        } catch (\RuntimeException $exception) {
            throw new InvalidBodyException($xml, $exception->getMessage(), 0, $exception);
        }

        $config = $this->configProvider->provide($merchantId);

        $signCheck = new IPay\SignCheckService($config);
        $signCheck->check($payment);

        return $payment;
    }

    /**
     * @param string $xml
     * @throws InvalidBodyException
     * @return Payment
     */
    protected function parseXml(string $xml): Payment
    {
        libxml_use_internal_errors(true);
        $object = simplexml_load_string($xml);
        libxml_clear_errors();
        libxml_use_internal_errors(false);

        if ($object === false) {
            throw new InvalidBodyException($xml, "Invalid XML");
        }

        $id = (int)$object->attributes()->id;
        if (empty($id)) {
            throw new InvalidBodyException($xml, "Missing ID");
        }
        $payment = new Payment(
            $id,
            (string)$object->ident,
            (int)$object->status,
            (int)$object->amount,
            (string)$object->currency,
            date_create()->setTimestamp((int)$object->timestamp),
            $this->parseTransactions($object),
            (string)$object->salt,
            (string)$object->sign
        );
        return $payment;
    }

    protected function parseTransactions(\SimpleXMLElement $element): Payments\TransactionCollection
    {
        if (!$element->transactions) {
            throw new InvalidBodyException($element->asXML(), "Missing transactions");
        }
        $transactions = [];
        foreach ($element->transactions->transaction as $plainTransaction) {
            $info = json_decode((string)$plainTransaction->info, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new InvalidBodyException(
                    $element->asXML(),
                    "Transaction Info: " . json_last_error_msg(),
                    json_last_error()
                );
            }

            $transaction = new IPay\Transaction(
                (int)$plainTransaction->attributes()->id,
                (int)$plainTransaction->amount / 100,
                (string)$plainTransaction->desc,
                (string)$plainTransaction->type,
                $info,
                (string)$plainTransaction->currency
            );
            $transactions[] = $transaction->setMerchantId((int)$plainTransaction->mch_id);
        }
        return new Payments\TransactionCollection($transactions);
    }

    protected function getMerchantId(Payments\TransactionCollection $transactions): int
    {
        foreach ($transactions as $transaction) {
            if ($transaction instanceof IPay\Transaction) {
                $merchantId = $transaction->getMerchantId();
                if (!empty($merchantId)) {
                    return $merchantId;
                }
            }
        }
        throw new \RuntimeException("Merchant Id can not be found");
    }
}
